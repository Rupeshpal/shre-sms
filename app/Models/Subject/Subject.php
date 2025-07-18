<?php

namespace App\Models\Subject;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use HasFactory;

    protected $table = 'subjects';

    protected $fillable = [
        'name',
        'code',
        'type',
        'full_mark_theory',
        'full_mark_practical',
        'pass_mark_theory',
        'pass_mark_practical',
        'status',
    ];
}