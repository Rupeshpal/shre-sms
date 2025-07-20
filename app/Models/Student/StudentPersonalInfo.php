<?php

namespace App\Models\Student;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentPersonalInfo extends Model
{
    use HasFactory;

    protected $table = 'student_personal_info';

    protected $fillable = [
        'academic_year',
        'admission_number',
        'admission_date',
        'roll_no',
        'status',
        'first_name',
        'last_name',
        'class',
        'section',
        'gender',
        'date_of_birth',
        'blood_group',
        'house',
        'mother_tongue',
        'contact_number',
        'email',
    ];
}