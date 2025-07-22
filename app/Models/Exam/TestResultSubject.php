<?php

namespace App\Models\Exam;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TestResultSubject extends Model
{
    use HasFactory;

    protected $table = 'test_result_subjects';
    protected $primaryKey = 'id';

    protected $fillable = [
        'testResultId',
        'name',
        'fullMarks',
        'passMarks',
        'obtainedMarks',
        'result',
    ];

    public function testResult()
    {
        return $this->belongsTo(TestResult::class, 'testResultId');
    }
}
