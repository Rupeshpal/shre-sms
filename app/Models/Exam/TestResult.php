<?php

namespace App\Models\Exam;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TestResult extends Model
{
    use HasFactory;

    protected $table = 'test_results';
    protected $primaryKey = 'id';

    public $timestamps = true;
    protected $casts = [
        'status' => 'boolean',
    ];

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
