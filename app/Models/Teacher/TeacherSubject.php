<?php

namespace App\Models\Teacher;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeacherSubject extends Model
{
    use HasFactory;

    protected $fillable = [
        'teacher_id',
        'subject_id',
    ];

    public function teacher()
    {
        return $this->belongsTo(\App\Models\Teacher\Teacher::class);
    }

    public function subject()
    {
        return $this->belongsTo(\App\Models\Subject\Subject::class);
    }
}
