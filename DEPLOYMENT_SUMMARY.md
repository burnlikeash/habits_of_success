# Student Performance Prediction Model - Deployment Summary

## Table of Contents
1. [Overview](#overview)
2. [Model Development Process](#model-development-process)
3. [Model Deployment](#model-deployment)
4. [User Interface and Interaction](#user-interface-and-interaction)
5. [API Endpoint Documentation](#api-endpoint-documentation)
6. [System Architecture](#system-architecture)

---

## Overview

This document summarizes the deployment of a machine learning model for predicting student academic performance based on various lifestyle and academic factors. The system consists of a FastAPI backend serving predictions and a Laravel-based web dashboard for user interaction.

**Key Features:**
- Real-time student performance prediction
- Interactive web dashboard with data visualization
- RESTful API for model inference
- Docker containerization for easy deployment

---

## Model Development Process

### 1. Model Selection and Training

#### Data Preprocessing
The model was trained on a comprehensive dataset containing student information across multiple dimensions:

**Data Sources:**
- Student demographics (age, gender)
- Academic metrics (exam scores, attendance percentage)
- Lifestyle factors (study hours, sleep hours, social media usage)
- Family background (parental education level, mental health rating)
- Habits (diet quality, exercise frequency, part-time job status)

**Preprocessing Steps:**
1. **Data Cleaning**: Handled missing values and outliers
2. **Feature Engineering**: Combined features from multiple tables (student, student_habits, student_lifestyle, student_academic, student_family)
3. **Categorical Encoding**: Used Label Encoding for categorical variables:
   - Gender (Male/Female/Other)
   - Diet Quality (Poor/Average/Good)
   - Part-time Job (Yes/No)
   - Extracurricular Participation (Yes/No)
   - Internet Quality (Poor/Average/Good)
   - Parental Education Level (High School/Bachelor/Master)
4. **Feature Scaling**: Applied StandardScaler to normalize numerical features
5. **Target Variable**: Binary classification (0 = Low Performance, 1 = Good Performance)

**Screenshot: Data Preprocessing Pipeline**
```
[IMAGE PLACEHOLDER: data_preprocessing_pipeline.png]
Caption: Data preprocessing workflow showing data cleaning, feature engineering, and encoding steps
```

#### Model Training and Parameter Tuning

**Model Selection:**
- **Algorithm**: Support Vector Machine (SVM)
- **Rationale**: SVM performs well on binary classification tasks with mixed data types and provides good generalization

**Hyperparameter Tuning:**
- Used grid search or random search for parameter optimization
- Key parameters tuned:
  - Kernel type (linear, RBF, polynomial)
  - C parameter (regularization strength)
  - Gamma parameter (for RBF kernel)

**Screenshot: Model Training Results**
```
[IMAGE PLACEHOLDER: model_training_results.png]
Caption: Training metrics showing accuracy, precision, recall, and F1-score during model development
```

#### Model Evaluation

**Evaluation Metrics:**
- Accuracy: [INSERT VALUE]
- Precision: [INSERT VALUE]
- Recall: [INSERT VALUE]
- F1-Score: [INSERT VALUE]
- Confusion Matrix analysis

**Cross-Validation:**
- Used k-fold cross-validation to ensure model robustness
- Evaluated on held-out test set

**Screenshot: Model Evaluation Metrics**
```
[IMAGE PLACEHOLDER: model_evaluation_metrics.png]
Caption: Confusion matrix and classification report showing model performance metrics
```

**Screenshot: ROC Curve**
```
[IMAGE PLACEHOLDER: roc_curve.png]
Caption: ROC curve showing model's true positive rate vs false positive rate
```

### 2. Saving the Trained Model

The trained model and preprocessing components were saved using joblib for efficient serialization:

**Saved Components:**
1. **SVM Model** (`svm_model.pkl`): The trained classifier
2. **Scaler** (`scaler.pkl`): StandardScaler for feature normalization
3. **Label Encoder** (`label_encoder.pkl`): Encoder for categorical variables

**Code Example:**
```python
import joblib

# Save model
joblib.dump(svm_model, 'models/svm_model.pkl')

# Save scaler
joblib.dump(scaler, 'models/scaler.pkl')

# Save encoder
joblib.dump(encoder, 'models/label_encoder.pkl')
```

**Screenshot: Model Files Structure**
```
[IMAGE PLACEHOLDER: model_files_structure.png]
Caption: Directory structure showing saved model files (svm_model.pkl, scaler.pkl, label_encoder.pkl)
```

---

## Model Deployment

### 3. Creating API Endpoint with FastAPI

#### API Architecture

The prediction API was built using FastAPI, providing:
- Fast, asynchronous request handling
- Automatic API documentation
- Type validation with Pydantic models
- CORS support for web frontend integration

#### API Implementation

**Endpoint Structure:**
- **Health Check**: `GET /health` - Verify API is running
- **Prediction**: `POST /predict` - Generate student performance prediction

**Key Components:**

1. **Request Model (Pydantic)**:
```python
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
```

2. **Model Loading**:
```python
MODEL_DIR = os.path.join(os.path.dirname(__file__), "models")
svm = joblib.load(os.path.join(MODEL_DIR, "svm_model.pkl"))
scaler = joblib.load(os.path.join(MODEL_DIR, "scaler.pkl"))
encoder = joblib.load(os.path.join(MODEL_DIR, "label_encoder.pkl"))
```

3. **Prediction Pipeline**:
   - Receive input data
   - Apply value mappings (e.g., "Highschool" → "High School")
   - Encode categorical variables
   - Scale numerical features
   - Generate prediction
   - Return human-readable result

**Screenshot: FastAPI Documentation**
```
[IMAGE PLACEHOLDER: fastapi_docs.png]
Caption: FastAPI automatic API documentation at /docs endpoint showing available endpoints
```

**Screenshot: API Endpoint Code**
```
[IMAGE PLACEHOLDER: api_endpoint_code.png]
Caption: Code snippet showing the /predict endpoint implementation
```

#### Docker Containerization

The API is containerized using Docker for consistent deployment:

**Dockerfile Structure:**
```dockerfile
FROM python:3.9-slim
WORKDIR /code/app
COPY requirements.txt .
RUN pip install -r requirements.txt
COPY . .
CMD ["uvicorn", "server:app", "--host", "0.0.0.0", "--port", "8000"]
```

**Screenshot: Docker Compose Configuration**
```
[IMAGE PLACEHOLDER: docker_compose.png]
Caption: docker-compose.yml showing service configuration for API, database, and web app
```

---

## User Interface and Interaction

### Dashboard Overview

The web dashboard provides an intuitive interface for viewing student metrics and generating predictions.

**Screenshot: Main Dashboard**
```
[IMAGE PLACEHOLDER: main_dashboard.png]
Caption: Main dashboard showing academic metrics cards and correlation analysis charts
```

### Academic Metrics Cards

The dashboard displays 8 key metrics:
1. Average Exam Score
2. Average Sleep Hours
3. Mental Health Rating
4. Average Study Hours
5. Average Attendance
6. Average Social Media Hours
7. Total Students
8. Average Netflix Hours

**Screenshot: Metrics Cards**
```
[IMAGE PLACEHOLDER: metrics_cards.png]
Caption: Academic metrics cards displaying key performance indicators
```

### Correlation Analysis Charts

The dashboard includes 8 interactive charts:
1. Social Media Hours vs Sleep Hours (Scatter Plot)
2. Study Hours vs Part-time Job (Bar Chart)
3. Diet Quality vs Exam Scores (Bar Chart)
4. Mental Health vs Exam Scores (Scatter Plot)
5. Exercise Frequency vs Exam Scores (Bar Chart)
6. Attendance vs Exam Scores (Scatter Plot)
7. Netflix Hours vs Sleep Hours (Scatter Plot)
8. Parental Education Level vs Exam Scores (Bar Chart)

**Screenshot: Correlation Charts**
```
[IMAGE PLACEHOLDER: correlation_charts.png]
Caption: Correlation analysis section showing various relationship visualizations
```

### Prediction Interface

#### Step 1: Opening the Prediction Modal

Users click the "Predict Student Performance" button in the header to open the prediction form.

**Screenshot: Predict Button**
```
[IMAGE PLACEHOLDER: predict_button.png]
Caption: Header showing "Predict Student Performance" button
```

#### Step 2: Filling the Form

The modal displays a comprehensive form with 14 input fields:

**Required Fields:**
- Age (integer, 1-100)
- Gender (dropdown: Male/Female/Other)
- Study Hours Per Day (float, 0-24)
- Social Media Hours (float, 0-24)
- Netflix Hours (float, 0-24)
- Part Time Job (dropdown: Yes/No)
- Attendance Percentage (float, 0-100)
- Sleep Hours (float, 0-24)
- Diet Quality (dropdown: Poor/Fair/Good)
- Exercise Frequency (dropdown: 0-7)
- Parental Education Level (dropdown: Highschool/Bachelor/Master)
- Internet Quality (dropdown: Poor/Average/Good)
- Mental Health Rating (integer, 1-10)
- Extracurricular Participation (dropdown: Yes/No)

**Screenshot: Prediction Form**
```
[IMAGE PLACEHOLDER: prediction_form.png]
Caption: Modal form showing all input fields for student performance prediction
```

#### Step 3: Submitting the Form

After filling all required fields, users click "Generate Prediction" to submit the form.

**Screenshot: Form Submission**
```
[IMAGE PLACEHOLDER: form_submission.png]
Caption: Form with sample data filled in, ready for submission
```

#### Step 4: Processing

During prediction, a loading indicator is displayed.

**Screenshot: Loading State**
```
[IMAGE PLACEHOLDER: loading_state.png]
Caption: Loading indicator showing "Processing prediction..." message
```

#### Step 5: Viewing Results

The prediction result is displayed with color-coded styling:
- **Green background**: "Student will have good performance"
- **Red background**: "Student will have low performance"

**Screenshot: Prediction Result - Good Performance**
```
[IMAGE PLACEHOLDER: prediction_result_good.png]
Caption: Green success message showing "Student will have good performance"
```

**Screenshot: Prediction Result - Low Performance**
```
[IMAGE PLACEHOLDER: prediction_result_low.png]
Caption: Red warning message showing "Student will have low performance"
```

### Error Handling

If an error occurs, the system displays a detailed error message to help users understand the issue.

**Screenshot: Error Message**
```
[IMAGE PLACEHOLDER: error_message.png]
Caption: Error display showing detailed error information when prediction fails
```

---

## API Endpoint Documentation

### Health Check Endpoint

**Endpoint:** `GET /health`

**Description:** Verifies that the API server is running

**Response:**
```json
{
  "status": "ok",
  "message": "API is running"
}
```

**Screenshot: Health Check Response**
```
[IMAGE PLACEHOLDER: health_check.png]
Caption: API health check endpoint response
```

### Prediction Endpoint

**Endpoint:** `POST /predict`

**Description:** Generates a student performance prediction based on input data

**Request Body:**
```json
{
  "age": 20,
  "gender": "Male",
  "study_hours_per_day": 5.0,
  "social_media_hours": 2.0,
  "netflix_hours": 1.0,
  "part_time_job": "No",
  "attendance_percentage": 85.0,
  "sleep_hours": 7.0,
  "diet_quality": "Fair",
  "exercise_frequency": 3,
  "parental_education_level": "Highschool",
  "internet_quality": "Good",
  "mental_health_rating": 8,
  "extracurricular_participation": "Yes"
}
```

**Response:**
```json
{
  "prediction": "Student will have good performance",
  "prediction_code": "1"
}
```

**Screenshot: API Request/Response**
```
[IMAGE PLACEHOLDER: api_request_response.png]
Caption: Example API request and response using Postman or similar tool
```

**Screenshot: FastAPI Interactive Docs**
```
[IMAGE PLACEHOLDER: fastapi_interactive_docs.png]
Caption: FastAPI interactive documentation showing the /predict endpoint with example request
```

---

## System Architecture

### Architecture Overview

The system follows a three-tier architecture:

1. **Frontend Layer**: Laravel web application (Port 8080)
2. **API Layer**: FastAPI service (Port 8000)
3. **Data Layer**: MySQL database (Port 3306)

**Screenshot: System Architecture Diagram**
```
[IMAGE PLACEHOLDER: system_architecture.png]
Caption: Architecture diagram showing frontend, API, and database layers with data flow
```

### Docker Services

**Services:**
- `db`: MySQL 8 database
- `api`: FastAPI prediction service
- `web_app`: Laravel dashboard application

**Screenshot: Docker Services**
```
[IMAGE PLACEHOLDER: docker_services.png]
Caption: Docker Compose services running (docker ps output or Docker Desktop view)
```

### Data Flow

1. User submits form data through web interface
2. Frontend sends POST request to FastAPI endpoint
3. API loads pre-trained model and preprocessing components
4. Input data is preprocessed (encoding, scaling)
5. Model generates prediction
6. Result is returned to frontend
7. Frontend displays prediction to user

**Screenshot: Data Flow Diagram**
```
[IMAGE PLACEHOLDER: data_flow.png]
Caption: Sequence diagram showing data flow from user input to prediction result
```

### Database Schema

The system uses a normalized database schema with the following tables:
- `student`: Core student information
- `student_habits`: Study and lifestyle habits
- `student_lifestyle`: Lifestyle factors
- `student_academic`: Academic performance data
- `student_family`: Family background information
- `student_predicted_performance`: Model predictions

**Screenshot: Database Schema**
```
[IMAGE PLACEHOLDER: database_schema.png]
Caption: ER diagram or database schema visualization showing table relationships
```

---

## Deployment Process Summary

### Step-by-Step Deployment

1. **Model Training**
   - Preprocess training data
   - Train SVM model with hyperparameter tuning
   - Evaluate model performance
   - Save model artifacts

2. **API Development**
   - Create FastAPI application
   - Load saved model components
   - Implement prediction endpoint
   - Add CORS middleware
   - Test API endpoints

3. **Frontend Integration**
   - Create prediction form interface
   - Implement API client (fetch requests)
   - Add error handling
   - Style prediction results

4. **Containerization**
   - Create Dockerfiles for each service
   - Configure docker-compose.yml
   - Build and run containers

5. **Testing**
   - Test API endpoints
   - Test frontend integration
   - Verify end-to-end prediction flow
   - Test error handling

**Screenshot: Deployment Process**
```
[IMAGE PLACEHOLDER: deployment_process.png]
Caption: Flowchart showing the complete deployment process from model training to production
```

---

## Conclusion

This deployment demonstrates a complete machine learning pipeline from model development to production deployment. The system provides:

- **Accessibility**: User-friendly web interface
- **Reliability**: Robust error handling and validation
- **Scalability**: Docker containerization for easy scaling
- **Maintainability**: Clean code structure and documentation
- **Performance**: Fast API responses with efficient model inference

The integration of FastAPI for the backend and Laravel for the frontend creates a seamless user experience for student performance prediction.

---

## Technical Stack

- **Backend API**: FastAPI (Python)
- **Frontend**: Laravel (PHP) with Blade templates
- **Database**: MySQL 8
- **Machine Learning**: scikit-learn (SVM)
- **Containerization**: Docker & Docker Compose
- **Visualization**: Chart.js

---

## Future Enhancements

- Model versioning and A/B testing
- Batch prediction capabilities
- Model retraining pipeline
- Performance monitoring and logging
- User authentication and prediction history
- Export prediction reports

---

**Document Version:** 1.0  
**Last Updated:** [INSERT DATE]  
**Author:** [INSERT NAME]

