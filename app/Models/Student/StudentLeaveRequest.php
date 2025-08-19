<?php

namespace App\Models\Student;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentLeaveRequest extends Model
{
    use HasFactory;

    protected $table = 'students_leave_requests';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $casts = [
        'leave_date'    => 'datetime',
        'end_date'      => 'datetime',
        'decision_date' => 'datetime',
        'created_at'    => 'datetime',
        'updated_at'    => 'datetime',
    ];

    protected $fillable = [
        'student_id',
        'leave_type',
        'leave_date',
        'end_date',
        'applied_on',
        'no_of_days',
        'status',
        'remarks',
        'approver_id',
        'decision_date',
        'class_id',
        'section_id',
        'academic_year_id',
    ];

    public function student()
    {
        return $this->belongsTo(\App\Models\Student\StudentPersonalInfo::class, 'student_id');
    }

    public function approver()
    {
        return $this->belongsTo(\App\Models\User::class, 'approver_id');
    }

    public function class()
    {
        return $this->belongsTo(\App\Models\Classes\Classes::class, 'class_id');
    }

    public function section()
    {
        return $this->belongsTo(\App\Models\Section\Section::class, 'section_id');
    }

    public function academic()
    {
        return $this->belongsTo(\App\Models\AcademicYear::class, 'academic_year_id');
    }
}
