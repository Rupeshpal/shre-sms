<?php

namespace App\Models\Teacher;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeacherRoutine extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';

    protected $fillable = [
        'id',
        'teacher_id',
        'section_id',
        'subject_id',
        'class_id',
        'day_of_week',
        'start_time',
        'end_time',
        'room',
    ];

    public function teacher()
    {
        return $this->belongsTo(\App\Models\Teacher\Teacher::class);
    }

    public function subject()
    {
        return $this->belongsTo(\App\Models\Subject\Subject::class);
    }

    public function section()
    {
        return $this->belongsTo(\App\Models\Section\Section::class, 'section_id', 'id');
    }

    public function class()
    {
        return $this->belongsTo(\App\Models\Classes\Classes::class, 'class_id', 'id');
    }
}
