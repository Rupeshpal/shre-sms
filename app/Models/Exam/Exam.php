<?php

namespace App\Models\Exam;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
    use HasFactory;

    protected $primaryKey = 'examId';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $table = 'exams';

    protected $fillable = [
        'class',
        'section',
        'subject',
        'date',
        'passMark',
        'startTime',
        'duration',
        'roomNo',
    ];
}
