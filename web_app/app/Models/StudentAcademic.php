<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentAcademic extends Model
{
    protected $table = 'student_academic';
    protected $primaryKey = 'student_id';
    public $timestamps = false;
}
