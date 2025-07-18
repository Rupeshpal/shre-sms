<?php

namespace App\Models\Teacher;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeacherBankDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'teacher_id',
        'account_name',
        'account_number',
        'bank_name',
        'branch_name',
        'pan_number',
        'basic_salary',
        'contract_type',
        'work_location',
        'work_shift',
        'date_of_leaving',
        'qualification',
        'work_experience',
    ];
}
