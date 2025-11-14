<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentPredictedPerformance extends Model
{
    protected $table = 'student_predicted_performance';
    protected $primaryKey = 'student_id';
    public $timestamps = false;
}
