<?php

namespace App\Models\Teacher;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class TeacherLeaveRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'teacher_id',
        'leave_type',
        'leave_date',
        'end_date',
        'no_of_days',
        'approver_id',
        'applied_on',
        'status',
        'remarks',
    ];

    /**
     * Get the approver (User) for this leave request.
     */
    public function approver()
    {
        return $this->belongsTo(User::class, 'approver_id');
    }
}