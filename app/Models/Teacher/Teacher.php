<?php

namespace App\Models\Teacher;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    use HasFactory;

    protected $fillable = [
        'teacher_code',
        'first_name',
        'last_name',
        'email',
        'primary_contact',
        'gender',
        'blood_group',
        'date_of_birth',
        'date_of_joining',
        'marital_status',
        'qualification',
        'work_experience',
        'father_name',
        'mother_name',
        'house',
        'mother_tongue',
        'status',
    ];
}
