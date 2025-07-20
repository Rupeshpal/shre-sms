<?php

namespace App\Models\Student;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentMotherInfo extends Model
{
    use HasFactory;

    protected $primaryKey = 'mother_id';
    protected $table = 'student_mother_info';
    protected $fillable = [
        'student_id',
        'name',
        'email',
        'phone_number',
        'occupation',
        'temporary_address',
        'permanent_address',
        'nationality',
        'monthly_income',
    ];

    public function student()
    {
        return $this->belongsTo(\App\Models\Student\StudentPersonalInfo::class, 'student_id');
    }
}
