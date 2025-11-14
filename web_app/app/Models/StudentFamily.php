<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentFamily extends Model
{
    protected $table = 'student_family';
    protected $primaryKey = 'student_id';
    public $timestamps = false;
}
