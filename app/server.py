import os
import pandas as pd
import joblib
import mysql.connector
from fastapi import FastAPI, HTTPException
from fastapi.middleware.cors import CORSMiddleware
from pydantic import BaseModel

app = FastAPI(title="Student Performance Prediction API")

# Add CORS middleware
app.add_middleware(
    CORSMiddleware,
    allow_origins=["*"],
    allow_credentials=True,
    allow_methods=["*"],
    allow_headers=["*"],
)

# Request model for prediction
class StudentPredictionRequest(BaseModel):
    age: int
    gender: str
    study_hours_per_day: float
    social_media_hours: float
    netflix_hours: float
    part_time_job: str
    attendance_percentage: float
    sleep_hours: float
    diet_quality: str
    exercise_frequency: int
    parental_education_level: str
    internet_quality: str
    mental_health_rating: int
    extracurricular_participation: str

# Load model files
MODEL_DIR = os.path.join(os.path.dirname(__file__), "models")
svm = joblib.load(os.path.join(MODEL_DIR, "svm_model.pkl"))
scaler = joblib.load(os.path.join(MODEL_DIR, "scaler.pkl"))
encoder = joblib.load(os.path.join(MODEL_DIR, "label_encoder.pkl"))

# DB config using environment variables
DB_CONFIG = {
    'host': os.getenv('DB_HOST', 'db'),
    'database': os.getenv('DB_NAME', 'student_performance'),
    'user': os.getenv('DB_USER', 'root'),
    'password': os.getenv('DB_PASSWORD', '')
}

def get_db_connection():
    try:
        return mysql.connector.connect(**DB_CONFIG)
    except mysql.connector.Error as e:
        print(f"DB connection error: {e}")
        return None

@app.get("/analyze")
def analyze_students():
    conn = get_db_connection()
    if not conn:
        raise HTTPException(status_code=500, detail="Database connection failed")
    
    cursor = conn.cursor(dictionary=True)

    # Fetch student data
    query = """
    SELECT s.student_id,
           s.age, s.gender,
           h.study_hours_per_day, h.social_media_hours, h.netflix_hours,
           h.sleep_hours, h.diet_quality, h.exercise_frequency,
           l.part_time_job, l.extracurricular_participation, l.internet_quality,
           a.exam_score, a.attendance_percentage,
           f.parental_education_level, f.mental_health_rating
    FROM student s
    LEFT JOIN student_habits h ON s.student_id = h.student_id
    LEFT JOIN student_lifestyle l ON s.student_id = l.student_id
    LEFT JOIN student_academic a ON s.student_id = a.student_id
    LEFT JOIN student_family f ON s.student_id = f.student_id
    """
    cursor.execute(query)
    rows = cursor.fetchall()

    if not rows:
        raise HTTPException(status_code=404, detail="No student data found")

    df = pd.DataFrame(rows)
    student_ids = df['student_id'].tolist()
    df_features = df.drop(columns=['student_id', 'exam_score'])

    # Handle categorical columns
    categorical_cols = ['gender', 'diet_quality', 'part_time_job',
                        'extracurricular_participation', 'internet_quality',
                        'parental_education_level']

    for col in categorical_cols:
        if col in df_features.columns:
            known_classes = set(encoder.classes_)
            df_features[col] = df_features[col].apply(
                lambda x: x if x in known_classes else list(known_classes)[0]
            )
            df_features[col] = encoder.transform(df_features[col].astype(str))

    # Ensure feature order matches scaler
    if hasattr(scaler, "feature_names_in_"):
        expected_features = scaler.feature_names_in_

        # Add missing columns with default 0
        for col in expected_features:
            if col not in df_features.columns:
                df_features[col] = 0

        # Drop unexpected columns
        df_features = df_features[expected_features]
    else:
        print("Warning: scaler does not have feature_names_in_, skipping alignment.")

    # Scale and predict
    X_scaled = scaler.transform(df_features)
    y_pred = svm.predict(X_scaled)
    y_prob = svm.decision_function(X_scaled)

    # Update predictions in DB (cast to strings for VARCHAR columns)
    update_query = """
    UPDATE student_predicted_performance
    SET predicted_performance = %s
    WHERE student_id = %s
    """
    for student_id, pred in zip(student_ids, y_pred):
        cursor.execute(update_query, (str(pred), student_id))
    conn.commit()

    cursor.close()
    conn.close()

    # Return JSON results (convert NumPy types to native Python types)
    results = [
        {
            "student_id": sid,
            "predicted_performance": str(pred),
            "confidence": float(prob)
        }
        for sid, pred, prob in zip(student_ids, y_pred, y_prob)
    ]

    return {"analysis_results": results}

@app.get("/health")
def health_check():
    """Health check endpoint"""
    return {"status": "ok", "message": "API is running"}

@app.post("/predict")
def predict_student_performance(request: StudentPredictionRequest):
    """
    Predict student performance based on input data.
    Returns: "Student will have good performance" or "Student will have low performance"
    """
    try:
        # Create a DataFrame with the input data
        input_data = {
            'age': [request.age],
            'gender': [request.gender],
            'study_hours_per_day': [request.study_hours_per_day],
            'social_media_hours': [request.social_media_hours],
            'netflix_hours': [request.netflix_hours],
            'sleep_hours': [request.sleep_hours],
            'diet_quality': [request.diet_quality],
            'exercise_frequency': [request.exercise_frequency],
            'part_time_job': [request.part_time_job],
            'extracurricular_participation': [request.extracurricular_participation],
            'internet_quality': [request.internet_quality],
            'attendance_percentage': [request.attendance_percentage],
            'parental_education_level': [request.parental_education_level],
            'mental_health_rating': [request.mental_health_rating]
        }
        
        df = pd.DataFrame(input_data)
        
        # Handle categorical columns
        categorical_cols = ['gender', 'diet_quality', 'part_time_job',
                            'extracurricular_participation', 'internet_quality',
                            'parental_education_level']
        
        # Map values to match training data format
        value_mapping = {
            'parental_education_level': {
                'Highschool': 'High School'  # Map to match training data
            },
            'diet_quality': {
                'Fair': 'Average'  # Map Fair to Average if that's what the model expects
            }
        }
        
        for col in categorical_cols:
            if col in df.columns:
                # Apply value mapping if exists
                if col in value_mapping:
                    df[col] = df[col].apply(
                        lambda x: value_mapping[col].get(x, x)
                    )
                
                known_classes = set(encoder.classes_)
                df[col] = df[col].apply(
                    lambda x: x if x in known_classes else list(known_classes)[0]
                )
                df[col] = encoder.transform(df[col].astype(str))
        
        # Ensure feature order matches scaler
        if hasattr(scaler, "feature_names_in_"):
            expected_features = scaler.feature_names_in_
            
            # Add missing columns with default 0
            for col in expected_features:
                if col not in df.columns:
                    df[col] = 0
            
            # Drop unexpected columns
            df = df[expected_features]
        else:
            print("Warning: scaler does not have feature_names_in_, skipping alignment.")
        
        # Scale and predict
        X_scaled = scaler.transform(df)
        y_pred = svm.predict(X_scaled)
        
        # Map prediction to readable output
        # Assuming 0 = low performance, 1 = good performance (or vice versa)
        # Based on the existing code, predictions are stored as strings "0" or "1"
        prediction_value = int(y_pred[0])
        
        # Map: 1 = good performance, 0 = low performance (adjust if needed)
        if prediction_value == 1:
            result = "Student will have good performance"
        else:
            result = "Student will have low performance"
        
        return {
            "prediction": result,
            "prediction_code": str(prediction_value)
        }
    
    except Exception as e:
        import traceback
        error_details = traceback.format_exc()
        print(f"Prediction error: {error_details}")
        raise HTTPException(status_code=500, detail=f"Prediction error: {str(e)}")
