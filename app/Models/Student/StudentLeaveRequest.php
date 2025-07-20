<?php

namespace App\Models\Student;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentLeaveRequest extends Model
{
    use HasFactory;
    protected $table = 'students_leave_requests';
    protected $primaryKey = 'id';

    protected $fillable = [
        'user_id',
        'leave_type',
        'leave_date',
        'end_date',
        'applied_on',
        'no_of_days',
        'status',
        'remarks',
        'approver_id',
        'decision_date',
    ];

    public function student()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }

    public function approver()
    {
        return $this->belongsTo(\App\Models\User::class, 'approver_id');
    }
}