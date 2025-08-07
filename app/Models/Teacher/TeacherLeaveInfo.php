<?php

namespace App\Models\Teacher;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeacherLeaveInfo extends Model
{
    use HasFactory;

    protected $fillable = [
        'teacher_id',
        'medical_leaves',
        'maternity_leaves',
        'casual_leaves',
        'sick_leaves',
    ];

    public function teacher()
    {
        return $this->belongsTo(Teacher::class, 'teacher_id');
    }
}
