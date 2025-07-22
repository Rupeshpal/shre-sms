<?php

namespace App\Models\Exam;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TestResult extends Model
{
    use HasFactory;

    protected $fillable = [
        'testName',
        'status',
        'rank',
        'totalMarks',
        'passMarks',
        'obtainedMarks',
        'passPercentage',
    ];
}
