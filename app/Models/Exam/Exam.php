<?php

namespace App\Models\Exam;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Classes\Classes;
use App\Models\Section\Section;
use App\Models\Subject\Subject;
// use App\Models\Student\StudentPersonalInfo;

class Exam extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $keyType = 'int';
    protected $table = 'exams';

    protected $casts=[
        'class'=>'integer',
        'section'=>'integer',
        'subject'=>'integer',
        'pass_mark' =>'integer',
        'full_mark' =>'integer',

    ];

 protected $fillable = [
    'class',
    'section',
    'subject',
    'date',
    'exam_type',
    'pass_mark',
    'full_mark',
    'end_time',
    'start_time',
    'duration',
    'room_no',
];





    public function classInfo()
{
    return $this->belongsTo(Classes::class, 'class', 'id');
}

public function sectionInfo()
{
    return $this->belongsTo(Section::class, 'section', 'id');
}

public function subjectInfo()
{
    return $this->belongsTo(Subject::class, 'subject', 'id');
}

}
