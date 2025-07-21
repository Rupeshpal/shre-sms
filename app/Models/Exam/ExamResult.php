<?php

namespace App\Models\Exam;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamResult extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $table = 'exam_results';

    protected $fillable = [
        'admissionNo',
        'name',
        'rollNo',
        'class',
        'section',
        'science',
        'chemistry',
        'math',
        'social',
        'obtainedMarks',
        'total',
        'percentage',
        'grade',
        'result'
    ];
}
