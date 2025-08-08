<?php

namespace App\Models\Assignment;
use App\Models\Classes\Classes;
use App\Models\Section\Section;
use App\Models\Subject\Subject;
use App\Models\Teacher\Teacher;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Assignment extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'max_marks',
        'teacher_id',
        'subject_id',
        'section_id',
        'class_id',
        'due_date',
        'attachment',
    ];

    protected $casts = [
    'due_date' => 'datetime',
    'teacher_id' => 'integer',
    'subject_id' => 'integer',
    'section_id' => 'integer',
    'class_id' => 'integer',
    ];
    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function section()
    {
        return $this->belongsTo(Section::class, 'section_id', 'id');
    }

    public function class()
    {
        return $this->belongsTo(Classes::class, 'class_id', 'id'); // adjust model name if needed
    }
}
