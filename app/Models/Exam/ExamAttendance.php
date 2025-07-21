<?php

namespace App\Models\Exam;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamAttendance extends Model
{
    use HasFactory;

    protected $table = 'exam_attendances';
    protected $primaryKey = 'admissionNo';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'admissionNo',
        'rollNo',
        'class',
        'section',
        'student',
        'science',
        'chemistry',
        'math',
        'social',
    ];
}
