<?php

namespace App\Models\Exam;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamStat extends Model
{
    use HasFactory;
    protected $table = 'exam_stats';
    protected $primaryKey = 'id';

    protected $fillable = [
        'heading',
        'value',
    ];
}
