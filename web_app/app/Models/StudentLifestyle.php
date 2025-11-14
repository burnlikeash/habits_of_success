<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentLifestyle extends Model
{
    protected $table = 'student_lifestyle';
    protected $primaryKey = 'student_id';
    public $timestamps = false;
}
