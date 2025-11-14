import pandas as pd
import mysql.connector

# Load CSV
csv_file = 'student_habits_performance.csv'
df = pd.read_csv(csv_file)

# Replace NaN with None for MySQL
df = df.where(pd.notnull(df), None)

# Connect to MySQL (root, no password)
conn = mysql.connector.connect(
    host='localhost',
    user='root',
    password='',
    database='student_performance'
)
cursor = conn.cursor()

# Insert into student table (core info)
for _, row in df.iterrows():
    cursor.execute("""
        INSERT INTO student (student_id, age, gender)
        VALUES (%s, %s, %s)
    """, (row['student_id'], row['age'], row['gender']))

# Insert into student_habits table
for _, row in df.iterrows():
    cursor.execute("""
        INSERT INTO student_habits (
            student_id, study_hours_per_day, social_media_hours, netflix_hours,
            sleep_hours, diet_quality, exercise_frequency
        )
        VALUES (%s, %s, %s, %s, %s, %s, %s)
    """, (
        row['student_id'], row['study_hours_per_day'], row['social_media_hours'],
        row['netflix_hours'], row['sleep_hours'], row['diet_quality'], row['exercise_frequency']
    ))

# Insert into student_academic table
for _, row in df.iterrows():
    cursor.execute("""
        INSERT INTO student_academic (
            student_id, exam_score, attendance_percentage
        )
        VALUES (%s, %s, %s)
    """, (
        row['student_id'], row['exam_score'], row['attendance_percentage']
    ))

# Insert into student_lifestyle table
for _, row in df.iterrows():
    cursor.execute("""
        INSERT INTO student_lifestyle (
            student_id, part_time_job, extracurricular_participation, internet_quality
        )
        VALUES (%s, %s, %s, %s)
    """, (
        row['student_id'], row['part_time_job'], row['extracurricular_participation'], row['internet_quality']
    ))

# Insert into student_family table
for _, row in df.iterrows():
    cursor.execute("""
        INSERT INTO student_family (
            student_id, parental_education_level, mental_health_rating
        )
        VALUES (%s, %s, %s)
    """, (
        row['student_id'], row['parental_education_level'], row['mental_health_rating']
    ))

# Insert empty predicted_performance entries
for _, row in df.iterrows():
    cursor.execute("""
        INSERT INTO student_predicted_performance (
            student_id, predicted_performance
        )
        VALUES (%s, %s)
    """, (
        row['student_id'], None
    ))

# Commit changes and close
conn.commit()
cursor.close()
conn.close()

print("CSV data imported into 'student_performance' grouped tables successfully!")
