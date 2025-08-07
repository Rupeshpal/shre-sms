<?php

namespace App\Models\Exam;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TestResultSubject extends Model
{
    use HasFactory;

    protected $table = 'test_result_subjects';
    protected $primaryKey = 'id';
    public $timestamps = true;
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'id',
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