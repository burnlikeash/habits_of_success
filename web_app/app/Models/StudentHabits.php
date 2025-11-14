<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentHabits extends Model
{
    protected $table = 'student_habits';
    protected $primaryKey = 'student_id';
    public $timestamps = false;
}
