<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\StudentAcademic;
use App\Models\StudentHabits;
use App\Models\StudentFamily;
use App\Models\StudentLifestyle;
use App\Models\StudentPredictedPerformance;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Academic Metrics
        $averageExamScore = StudentAcademic::avg('exam_score');
        $averageSleepHours = StudentHabits::avg('sleep_hours');
        $averageMentalHealth = StudentFamily::avg('mental_health_rating');
        $averageStudyHours = StudentHabits::avg('study_hours_per_day');
        $averageAttendance = StudentAcademic::avg('attendance_percentage');
        $averageSocialMediaHours = StudentHabits::avg('social_media_hours');
        $averageNetflixHours = StudentHabits::avg('netflix_hours');
        $totalStudents = Student::count();

        // Correlation Analysis Data
        // Social Media Hours vs Sleep Hours (scatter plot data)
        $socialMediaSleepData = DB::table('student_habits')
            ->select('social_media_hours', 'sleep_hours')
            ->whereNotNull('social_media_hours')
            ->whereNotNull('sleep_hours')
            ->get()
            ->map(function($item) {
                return [
                    'x' => (float)$item->social_media_hours,
                    'y' => (float)$item->sleep_hours
                ];
            });

        // Study Hours vs Part-time Job (bar chart data)
        // Group by students with and without part-time jobs
        $studyHoursWithJob = DB::table('student_habits')
            ->join('student_lifestyle', 'student_habits.student_id', '=', 'student_lifestyle.student_id')
            ->where('student_lifestyle.part_time_job', 'Yes')
            ->avg('student_habits.study_hours_per_day');

        $studyHoursWithoutJob = DB::table('student_habits')
            ->join('student_lifestyle', 'student_habits.student_id', '=', 'student_lifestyle.student_id')
            ->where('student_lifestyle.part_time_job', 'No')
            ->avg('student_habits.study_hours_per_day');

        // Estimate part-time job hours (average 10-12 hours for those with jobs)
        // For visualization, we'll use a reasonable estimate
        $avgJobHours = 11; // Average hours for students with part-time jobs

        $studyJobData = [
            'with_job' => [
                'study_hours' => $studyHoursWithJob ?: 0,
                'job_hours' => $avgJobHours
            ],
            'without_job' => [
                'study_hours' => $studyHoursWithoutJob ?: 0,
                'job_hours' => 0
            ]
        ];

        // Diet Quality vs Exam Scores (bar chart - average exam score by diet quality)
        $dietQualityData = DB::table('student_habits')
            ->join('student_academic', 'student_habits.student_id', '=', 'student_academic.student_id')
            ->select('student_habits.diet_quality', DB::raw('AVG(student_academic.exam_score) as avg_exam_score'))
            ->whereNotNull('student_habits.diet_quality')
            ->whereNotNull('student_academic.exam_score')
            ->groupBy('student_habits.diet_quality')
            ->get()
            ->map(function($item) {
                return [
                    'diet_quality' => $item->diet_quality,
                    'avg_exam_score' => (float)$item->avg_exam_score
                ];
            });

        // Mental Health vs Exam Scores (scatter plot)
        $mentalHealthExamData = DB::table('student_family')
            ->join('student_academic', 'student_family.student_id', '=', 'student_academic.student_id')
            ->select('student_family.mental_health_rating', 'student_academic.exam_score')
            ->whereNotNull('student_family.mental_health_rating')
            ->whereNotNull('student_academic.exam_score')
            ->get()
            ->map(function($item) {
                return [
                    'x' => (float)$item->mental_health_rating,
                    'y' => (float)$item->exam_score
                ];
            });

        // Exercise Frequency vs Exam Scores (bar chart - average exam score by exercise frequency)
        $exerciseFrequencyData = DB::table('student_habits')
            ->join('student_academic', 'student_habits.student_id', '=', 'student_academic.student_id')
            ->select('student_habits.exercise_frequency', DB::raw('AVG(student_academic.exam_score) as avg_exam_score'))
            ->whereNotNull('student_habits.exercise_frequency')
            ->whereNotNull('student_academic.exam_score')
            ->groupBy('student_habits.exercise_frequency')
            ->get()
            ->map(function($item) {
                return [
                    'exercise_frequency' => $item->exercise_frequency,
                    'avg_exam_score' => (float)$item->avg_exam_score
                ];
            });

        // Attendance vs Exam Scores (scatter plot)
        $attendanceExamData = DB::table('student_academic')
            ->select('attendance_percentage', 'exam_score')
            ->whereNotNull('attendance_percentage')
            ->whereNotNull('exam_score')
            ->get()
            ->map(function($item) {
                return [
                    'x' => (float)$item->attendance_percentage,
                    'y' => (float)$item->exam_score
                ];
            });

        // Netflix Hours vs Sleep Hours (scatter plot)
        $netflixSleepData = DB::table('student_habits')
            ->select('netflix_hours', 'sleep_hours')
            ->whereNotNull('netflix_hours')
            ->whereNotNull('sleep_hours')
            ->get()
            ->map(function($item) {
                return [
                    'x' => (float)$item->netflix_hours,
                    'y' => (float)$item->sleep_hours
                ];
            });

        // Parental Education Level vs Exam Scores (bar chart)
        $parentalEducationData = DB::table('student_family')
            ->join('student_academic', 'student_family.student_id', '=', 'student_academic.student_id')
            ->select('student_family.parental_education_level', DB::raw('AVG(student_academic.exam_score) as avg_exam_score'))
            ->whereNotNull('student_family.parental_education_level')
            ->whereNotNull('student_academic.exam_score')
            ->groupBy('student_family.parental_education_level')
            ->get()
            ->map(function($item) {
                return [
                    'education_level' => $item->parental_education_level,
                    'avg_exam_score' => (float)$item->avg_exam_score
                ];
            });

        return view('dashboard', compact(
            'averageExamScore',
            'averageSleepHours',
            'averageMentalHealth',
            'averageStudyHours',
            'averageAttendance',
            'averageSocialMediaHours',
            'averageNetflixHours',
            'totalStudents',
            'socialMediaSleepData',
            'studyJobData',
            'dietQualityData',
            'mentalHealthExamData',
            'exerciseFrequencyData',
            'attendanceExamData',
            'netflixSleepData',
            'parentalEducationData'
        ));
    }
}
