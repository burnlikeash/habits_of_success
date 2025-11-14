<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Habits of Success</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background: #f5f5f5;
            color: #333;
            line-height: 1.6;
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 20px;
        }

        /* Header */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            padding: 20px 0;
        }

        .logo-container {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .logo {
            display: flex;
            gap: -5px;
        }

        .logo-book {
            width: 32px;
            height: 32px;
            border-radius: 4px;
            position: relative;
        }

        .logo-book.green {
            background: #4CAF50;
            z-index: 2;
        }

        .logo-book.red {
            background: #F44336;
            margin-left: -8px;
            z-index: 1;
        }

        .logo-text {
            font-size: 24px;
            font-weight: 600;
            color: #333;
        }

        .generate-report-btn {
            background: #2196F3;
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 6px;
            font-size: 16px;
            font-weight: 500;
            cursor: pointer;
            transition: background 0.3s;
        }

        .generate-report-btn:hover {
            background: #1976D2;
        }

        /* Section Headers */
        .section-header {
            font-size: 28px;
            font-weight: 600;
            margin-bottom: 24px;
            color: #1a1a1a;
        }

        /* Academic Metrics Cards */
        .metrics-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 24px;
            margin-bottom: 50px;
        }

        .metric-card {
            background: white;
            border-radius: 12px;
            padding: 28px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .metric-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.12);
        }

        .metric-label {
            font-size: 14px;
            color: #666;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 12px;
            font-weight: 500;
        }

        .metric-value {
            font-size: 48px;
            font-weight: 700;
            color: #1565C0;
            margin-bottom: 8px;
        }

        .metric-context {
            font-size: 14px;
            color: #888;
            margin-bottom: 12px;
        }

        .metric-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
        }

        .badge-positive {
            background: #E8F5E9;
            color: #2E7D32;
        }

        .badge-healthy {
            background: #E3F2FD;
            color: #1976D2;
        }

        .badge-excellent {
            background: #FCE4EC;
            color: #C2185B;
        }

        .badge-icon {
            width: 16px;
            height: 16px;
        }

        /* Correlation Analysis */
        .charts-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(500px, 1fr));
            gap: 24px;
            margin-bottom: 30px;
        }

        .chart-card {
            background: white;
            border-radius: 12px;
            padding: 24px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }

        .chart-title {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 20px;
            color: #333;
        }

        .chart-container {
            position: relative;
            height: 300px;
        }

        @media (max-width: 768px) {
            .charts-grid {
                grid-template-columns: 1fr;
            }
            
            .metrics-grid {
                grid-template-columns: 1fr;
            }
        }

        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0,0,0,0.5);
        }

        .modal-content {
            background-color: #fefefe;
            margin: 5% auto;
            padding: 30px;
            border: none;
            border-radius: 12px;
            width: 90%;
            max-width: 600px;
            max-height: 90vh;
            overflow-y: auto;
            box-shadow: 0 4px 20px rgba(0,0,0,0.3);
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
            padding-bottom: 16px;
            border-bottom: 2px solid #e0e0e0;
        }

        .modal-title {
            font-size: 24px;
            font-weight: 600;
            color: #333;
        }

        .close {
            color: #aaa;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
            border: none;
            background: none;
            padding: 0;
            width: 30px;
            height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .close:hover,
        .close:focus {
            color: #000;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: #333;
            font-size: 14px;
        }

        .form-group input,
        .form-group select {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 14px;
            transition: border-color 0.3s;
        }

        .form-group input:focus,
        .form-group select:focus {
            outline: none;
            border-color: #2196F3;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
        }

        .submit-btn {
            background: #2196F3;
            color: white;
            border: none;
            padding: 14px 28px;
            border-radius: 6px;
            font-size: 16px;
            font-weight: 500;
            cursor: pointer;
            width: 100%;
            margin-top: 10px;
            transition: background 0.3s;
        }

        .submit-btn:hover {
            background: #1976D2;
        }

        .submit-btn:disabled {
            background: #ccc;
            cursor: not-allowed;
        }

        .result-container {
            margin-top: 24px;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
            display: none;
        }

        .result-container.show {
            display: block;
        }

        .result-container.success {
            background: #E8F5E9;
            color: #2E7D32;
            border: 2px solid #4CAF50;
        }

        .result-container.low {
            background: #FFEBEE;
            color: #C62828;
            border: 2px solid #F44336;
        }

        .result-text {
            font-size: 18px;
            font-weight: 600;
            margin: 0;
        }

        .loading {
            display: none;
            text-align: center;
            padding: 20px;
        }

        .loading.show {
            display: block;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <div class="logo-container">
                <div class="logo">
                    <div class="logo-book green"></div>
                    <div class="logo-book red"></div>
                </div>
                <span class="logo-text">Habits of Success</span>
            </div>
            <button class="generate-report-btn" onclick="openModal()">Predict Student Performance</button>
        </div>

        <!-- Prediction Modal -->
        <div id="predictionModal" class="modal">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title">Generate Student Performance Report</h2>
                    <button class="close" onclick="closeModal()">&times;</button>
                </div>
                <form id="predictionForm" onsubmit="submitPrediction(event)">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="age">Age *</label>
                            <input type="number" id="age" name="age" required min="1" max="100">
                        </div>
                        <div class="form-group">
                            <label for="gender">Gender *</label>
                            <select id="gender" name="gender" required>
                                <option value="">Select Gender</option>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="study_hours_per_day">Study Hours Per Day *</label>
                            <input type="number" id="study_hours_per_day" name="study_hours_per_day" required step="0.1" min="0" max="24">
                        </div>
                        <div class="form-group">
                            <label for="social_media_hours">Social Media Hours *</label>
                            <input type="number" id="social_media_hours" name="social_media_hours" required step="0.1" min="0" max="24">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="netflix_hours">Netflix Hours *</label>
                            <input type="number" id="netflix_hours" name="netflix_hours" required step="0.1" min="0" max="24">
                        </div>
                        <div class="form-group">
                            <label for="sleep_hours">Sleep Hours *</label>
                            <input type="number" id="sleep_hours" name="sleep_hours" required step="0.1" min="0" max="24">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="attendance_percentage">Attendance Percentage *</label>
                            <input type="number" id="attendance_percentage" name="attendance_percentage" required step="0.1" min="0" max="100">
                        </div>
                        <div class="form-group">
                            <label for="mental_health_rating">Mental Health Rating (1-10) *</label>
                            <input type="number" id="mental_health_rating" name="mental_health_rating" required min="1" max="10">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="part_time_job">Part Time Job *</label>
                            <select id="part_time_job" name="part_time_job" required>
                                <option value="">Select</option>
                                <option value="Yes">Yes</option>
                                <option value="No">No</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="extracurricular_participation">Extracurricular Participation *</label>
                            <select id="extracurricular_participation" name="extracurricular_participation" required>
                                <option value="">Select</option>
                                <option value="Yes">Yes</option>
                                <option value="No">No</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="diet_quality">Diet Quality *</label>
                            <select id="diet_quality" name="diet_quality" required>
                                <option value="">Select</option>
                                <option value="Poor">Poor</option>
                                <option value="Fair">Fair</option>
                                <option value="Good">Good</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="exercise_frequency">Exercise Frequency (0-7) *</label>
                            <select id="exercise_frequency" name="exercise_frequency" required>
                                <option value="">Select</option>
                                <option value="0">0</option>
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
                                <option value="6">6</option>
                                <option value="7">7</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="parental_education_level">Parental Education Level *</label>
                            <select id="parental_education_level" name="parental_education_level" required>
                                <option value="">Select</option>
                                <option value="Highschool">Highschool</option>
                                <option value="Bachelor">Bachelor</option>
                                <option value="Master">Master</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="internet_quality">Internet Quality *</label>
                            <select id="internet_quality" name="internet_quality" required>
                                <option value="">Select</option>
                                <option value="Poor">Poor</option>
                                <option value="Average">Average</option>
                                <option value="Good">Good</option>
                            </select>
                        </div>
                    </div>

                    <div class="loading" id="loading">
                        <p>Processing prediction...</p>
                    </div>

                    <div class="result-container" id="resultContainer">
                        <p class="result-text" id="resultText"></p>
                    </div>

                    <button type="submit" class="submit-btn" id="submitBtn">Generate Prediction</button>
                </form>
            </div>
        </div>

        <!-- Your Academic Metrics Section -->
        <h2 class="section-header">Your Academic Metrics</h2>
        <div class="metrics-grid">
            <!-- Average Exam Score Card -->
            <div class="metric-card">
                <div class="metric-label">Average Exam Score</div>
                <div class="metric-value">{{ number_format($averageExamScore, 1) }}</div>
                <div class="metric-context">out of 100</div>
            </div>

            <!-- Average Sleep Hours Card -->
            <div class="metric-card">
                <div class="metric-label">Average Sleep Hours</div>
                <div class="metric-value">{{ number_format($averageSleepHours, 1) }}</div>
                <div class="metric-context">hours per night</div>
            </div>

            <!-- Mental Health Rating Card -->
            <div class="metric-card">
                <div class="metric-label">Mental Health Rating</div>
                <div class="metric-value">{{ number_format($averageMentalHealth, 1) }}</div>
                <div class="metric-context">out of 10</div>
            </div>

            <!-- Average Study Hours Card -->
            <div class="metric-card">
                <div class="metric-label">Average Study Hours</div>
                <div class="metric-value">{{ number_format($averageStudyHours, 1) }}</div>
                <div class="metric-context">hours per day</div>
            </div>

            <!-- Average Attendance Card -->
            <div class="metric-card">
                <div class="metric-label">Average Attendance</div>
                <div class="metric-value">{{ number_format($averageAttendance, 1) }}</div>
                <div class="metric-context">percentage</div>
            </div>

            <!-- Average Social Media Hours Card -->
            <div class="metric-card">
                <div class="metric-label">Average Social Media Hours</div>
                <div class="metric-value">{{ number_format($averageSocialMediaHours, 1) }}</div>
                <div class="metric-context">hours per day</div>
            </div>

            <!-- Total Students Card -->
            <div class="metric-card">
                <div class="metric-label">Total Students</div>
                <div class="metric-value">{{ number_format($totalStudents, 0) }}</div>
                <div class="metric-context">students</div>
            </div>

            <!-- Average Netflix Hours Card -->
            <div class="metric-card">
                <div class="metric-label">Average Netflix Hours</div>
                <div class="metric-value">{{ number_format($averageNetflixHours, 1) }}</div>
                <div class="metric-context">hours per day</div>
            </div>
        </div>

        <!-- Correlation Analysis Section -->
        <h2 class="section-header">Correlation Analysis</h2>
        <div class="charts-grid">
            <!-- Social Media Hours vs Sleep Hours Scatter Plot -->
            <div class="chart-card">
                <div class="chart-title">Social Media Hours vs Sleep Hours</div>
                <div class="chart-container">
                    <canvas id="socialMediaSleepChart"></canvas>
                </div>
            </div>

            <!-- Study Hours vs Part-time Job Bar Chart -->
            <div class="chart-card">
                <div class="chart-title">Study Hours vs Part-time Job</div>
                <div class="chart-container">
                    <canvas id="studyJobChart"></canvas>
                </div>
            </div>

            <!-- Diet Quality vs Exam Scores Bar Chart -->
            <div class="chart-card">
                <div class="chart-title">Diet Quality vs Exam Scores</div>
                <div class="chart-container">
                    <canvas id="dietQualityChart"></canvas>
                </div>
            </div>

            <!-- Mental Health vs Exam Scores Scatter Plot -->
            <div class="chart-card">
                <div class="chart-title">Mental Health vs Exam Scores</div>
                <div class="chart-container">
                    <canvas id="mentalHealthExamChart"></canvas>
                </div>
            </div>

            <!-- Exercise Frequency vs Exam Scores Bar Chart -->
            <div class="chart-card">
                <div class="chart-title">Exercise Frequency vs Exam Scores</div>
                <div class="chart-container">
                    <canvas id="exerciseFrequencyChart"></canvas>
                </div>
            </div>

            <!-- Attendance vs Exam Scores Scatter Plot -->
            <div class="chart-card">
                <div class="chart-title">Attendance vs Exam Scores</div>
                <div class="chart-container">
                    <canvas id="attendanceExamChart"></canvas>
                </div>
            </div>

            <!-- Netflix Hours vs Sleep Hours Scatter Plot -->
            <div class="chart-card">
                <div class="chart-title">Netflix Hours vs Sleep Hours</div>
                <div class="chart-container">
                    <canvas id="netflixSleepChart"></canvas>
                </div>
            </div>

            <!-- Parental Education Level vs Exam Scores Bar Chart -->
            <div class="chart-card">
                <div class="chart-title">Parental Education Level vs Exam Scores</div>
                <div class="chart-container">
                    <canvas id="parentalEducationChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Social Media Hours vs Sleep Hours Scatter Plot
        const socialMediaSleepCtx = document.getElementById('socialMediaSleepChart').getContext('2d');
        const socialMediaSleepData = @json($socialMediaSleepData);
        
        new Chart(socialMediaSleepCtx, {
            type: 'scatter',
            data: {
                datasets: [{
                    label: 'Students',
                    data: socialMediaSleepData,
                    backgroundColor: 'rgba(33, 150, 243, 0.6)',
                    borderColor: 'rgba(33, 150, 243, 1)',
                    pointRadius: 6,
                    pointHoverRadius: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    x: {
                        title: {
                            display: true,
                            text: 'Social Media Hours'
                        },
                        min: 0
                    },
                    y: {
                        title: {
                            display: true,
                            text: 'Sleep Hours'
                        },
                        min: 4,
                        max: 9
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });

        // Study Hours vs Part-time Job Bar Chart
        const studyJobCtx = document.getElementById('studyJobChart').getContext('2d');
        const studyJobData = @json($studyJobData);
        
        new Chart(studyJobCtx, {
            type: 'bar',
            data: {
                labels: ['With Part-time Job', 'Without Part-time Job'],
                datasets: [
                    {
                        label: 'Study Hours',
                        data: [
                            studyJobData.with_job.study_hours,
                            studyJobData.without_job.study_hours
                        ],
                        backgroundColor: 'rgba(21, 101, 192, 0.8)',
                        borderColor: 'rgba(21, 101, 192, 1)',
                        borderWidth: 1
                    },
                    {
                        label: 'Part-time Job Hours',
                        data: [
                            studyJobData.with_job.job_hours,
                            studyJobData.without_job.job_hours
                        ],
                        backgroundColor: 'rgba(100, 181, 246, 0.8)',
                        borderColor: 'rgba(100, 181, 246, 1)',
                        borderWidth: 1
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        title: {
                            display: true,
                            text: 'Hours'
                        },
                        beginAtZero: true,
                        min: 0,
                        max: 20
                    }
                },
                plugins: {
                    legend: {
                        display: true,
                        position: 'top'
                    }
                }
            }
        });

        // Diet Quality vs Exam Scores Bar Chart
        const dietQualityCtx = document.getElementById('dietQualityChart').getContext('2d');
        const dietQualityData = @json($dietQualityData);
        
        const dietLabels = dietQualityData.map(item => item.diet_quality);
        const dietScores = dietQualityData.map(item => item.avg_exam_score);
        
        new Chart(dietQualityCtx, {
            type: 'bar',
            data: {
                labels: dietLabels,
                datasets: [{
                    label: 'Average Exam Score',
                    data: dietScores,
                    backgroundColor: 'rgba(76, 175, 80, 0.8)',
                    borderColor: 'rgba(76, 175, 80, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        title: {
                            display: true,
                            text: 'Average Exam Score'
                        },
                        beginAtZero: true,
                        min: 0,
                        max: 100
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Diet Quality'
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });

        // Mental Health vs Exam Scores Scatter Plot
        const mentalHealthExamCtx = document.getElementById('mentalHealthExamChart').getContext('2d');
        const mentalHealthExamData = @json($mentalHealthExamData);
        
        new Chart(mentalHealthExamCtx, {
            type: 'scatter',
            data: {
                datasets: [{
                    label: 'Students',
                    data: mentalHealthExamData,
                    backgroundColor: 'rgba(156, 39, 176, 0.6)',
                    borderColor: 'rgba(156, 39, 176, 1)',
                    pointRadius: 6,
                    pointHoverRadius: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    x: {
                        title: {
                            display: true,
                            text: 'Mental Health Rating'
                        },
                        min: 0,
                        max: 10
                    },
                    y: {
                        title: {
                            display: true,
                            text: 'Exam Score'
                        },
                        min: 0,
                        max: 100
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });

        // Exercise Frequency vs Exam Scores Bar Chart
        const exerciseFrequencyCtx = document.getElementById('exerciseFrequencyChart').getContext('2d');
        const exerciseFrequencyData = @json($exerciseFrequencyData);
        
        const exerciseLabels = exerciseFrequencyData.map(item => item.exercise_frequency);
        const exerciseScores = exerciseFrequencyData.map(item => item.avg_exam_score);
        
        new Chart(exerciseFrequencyCtx, {
            type: 'bar',
            data: {
                labels: exerciseLabels,
                datasets: [{
                    label: 'Average Exam Score',
                    data: exerciseScores,
                    backgroundColor: 'rgba(255, 152, 0, 0.8)',
                    borderColor: 'rgba(255, 152, 0, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        title: {
                            display: true,
                            text: 'Average Exam Score'
                        },
                        beginAtZero: true,
                        min: 0,
                        max: 100
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Exercise Frequency'
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });

        // Attendance vs Exam Scores Scatter Plot
        const attendanceExamCtx = document.getElementById('attendanceExamChart').getContext('2d');
        const attendanceExamData = @json($attendanceExamData);
        
        new Chart(attendanceExamCtx, {
            type: 'scatter',
            data: {
                datasets: [{
                    label: 'Students',
                    data: attendanceExamData,
                    backgroundColor: 'rgba(0, 150, 136, 0.6)',
                    borderColor: 'rgba(0, 150, 136, 1)',
                    pointRadius: 6,
                    pointHoverRadius: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    x: {
                        title: {
                            display: true,
                            text: 'Attendance Percentage'
                        },
                        min: 0,
                        max: 100
                    },
                    y: {
                        title: {
                            display: true,
                            text: 'Exam Score'
                        },
                        min: 0,
                        max: 100
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });

        // Netflix Hours vs Sleep Hours Scatter Plot
        const netflixSleepCtx = document.getElementById('netflixSleepChart').getContext('2d');
        const netflixSleepData = @json($netflixSleepData);
        
        new Chart(netflixSleepCtx, {
            type: 'scatter',
            data: {
                datasets: [{
                    label: 'Students',
                    data: netflixSleepData,
                    backgroundColor: 'rgba(233, 30, 99, 0.6)',
                    borderColor: 'rgba(233, 30, 99, 1)',
                    pointRadius: 6,
                    pointHoverRadius: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    x: {
                        title: {
                            display: true,
                            text: 'Netflix Hours'
                        },
                        min: 0
                    },
                    y: {
                        title: {
                            display: true,
                            text: 'Sleep Hours'
                        },
                        min: 4,
                        max: 9
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });

        // Parental Education Level vs Exam Scores Bar Chart
        const parentalEducationCtx = document.getElementById('parentalEducationChart').getContext('2d');
        const parentalEducationData = @json($parentalEducationData);
        
        const educationLabels = parentalEducationData.map(item => item.education_level);
        const educationScores = parentalEducationData.map(item => item.avg_exam_score);
        
        new Chart(parentalEducationCtx, {
            type: 'bar',
            data: {
                labels: educationLabels,
                datasets: [{
                    label: 'Average Exam Score',
                    data: educationScores,
                    backgroundColor: 'rgba(63, 81, 181, 0.8)',
                    borderColor: 'rgba(63, 81, 181, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        title: {
                            display: true,
                            text: 'Average Exam Score'
                        },
                        beginAtZero: true,
                        min: 0,
                        max: 100
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Parental Education Level'
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });

        // Modal Functions
        function openModal() {
            document.getElementById('predictionModal').style.display = 'block';
            document.getElementById('resultContainer').classList.remove('show');
            document.getElementById('predictionForm').reset();
        }

        function closeModal() {
            document.getElementById('predictionModal').style.display = 'none';
            document.getElementById('resultContainer').classList.remove('show');
            document.getElementById('loading').classList.remove('show');
            document.getElementById('predictionForm').reset();
        }

        // Close modal when clicking outside of it
        window.onclick = function(event) {
            const modal = document.getElementById('predictionModal');
            if (event.target == modal) {
                closeModal();
            }
        }

        // Form Submission
        async function submitPrediction(event) {
            event.preventDefault();
            
            const submitBtn = document.getElementById('submitBtn');
            const loading = document.getElementById('loading');
            const resultContainer = document.getElementById('resultContainer');
            const resultText = document.getElementById('resultText');
            
            // Disable submit button and show loading
            submitBtn.disabled = true;
            loading.classList.add('show');
            resultContainer.classList.remove('show');
            
            // Get form data
            const formData = {
                age: parseInt(document.getElementById('age').value),
                gender: document.getElementById('gender').value,
                study_hours_per_day: parseFloat(document.getElementById('study_hours_per_day').value),
                social_media_hours: parseFloat(document.getElementById('social_media_hours').value),
                netflix_hours: parseFloat(document.getElementById('netflix_hours').value),
                part_time_job: document.getElementById('part_time_job').value,
                attendance_percentage: parseFloat(document.getElementById('attendance_percentage').value),
                sleep_hours: parseFloat(document.getElementById('sleep_hours').value),
                diet_quality: document.getElementById('diet_quality').value,
                exercise_frequency: parseInt(document.getElementById('exercise_frequency').value),
                parental_education_level: document.getElementById('parental_education_level').value,
                internet_quality: document.getElementById('internet_quality').value,
                mental_health_rating: parseInt(document.getElementById('mental_health_rating').value),
                extracurricular_participation: document.getElementById('extracurricular_participation').value
            };
            
            try {
                // Call the API endpoint
                const response = await fetch('http://localhost:8000/predict', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(formData)
                });
                
                if (!response.ok) {
                    const errorData = await response.json().catch(() => ({ detail: 'Unknown error' }));
                    throw new Error(errorData.detail || `HTTP ${response.status}: ${response.statusText}`);
                }
                
                const data = await response.json();
                
                // Hide loading and show result
                loading.classList.remove('show');
                resultText.textContent = data.prediction;
                
                // Style result based on prediction
                resultContainer.classList.remove('success', 'low');
                if (data.prediction.includes('good')) {
                    resultContainer.classList.add('success', 'show');
                } else {
                    resultContainer.classList.add('low', 'show');
                }
                
            } catch (error) {
                console.error('Error:', error);
                loading.classList.remove('show');
                
                // Try to get more detailed error message
                let errorMessage = 'Error: Could not generate prediction. ';
                if (error.message) {
                    errorMessage += error.message;
                } else {
                    errorMessage += 'Please check if the API server is running on port 8000.';
                }
                
                resultText.textContent = errorMessage;
                resultContainer.classList.add('low', 'show');
            } finally {
                submitBtn.disabled = false;
            }
        }
    </script>
</body>
</html>
