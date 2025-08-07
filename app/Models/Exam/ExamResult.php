<?php

namespace App\Models\Exam;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Classes\Classes;
use App\Models\Section\Section;
use App\Models\Subject\Subject;
class ExamResult extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $keyType = 'int';
    protected $table = 'exam_results';

    protected $fillable = [
        'id',
        'admissionNo',
        'name',
        'rollNo',
        'classId',
        'sectionId',
        'science',
        'chemistry',
        'math',
        'social',
        'obtainedMarks',
        'total',
        'percentage',
        'grade',
        'result'
    ];

           public function classInfo()
      {
          return $this->belongsTo(Classes::class, 'classId', 'id');
      }

      public function sectionInfo()
      {
          return $this->belongsTo(Section::class, 'sectionId', 'id');
      }

      public function subjectInfo()
      {
          return $this->belongsTo(Subject::class, 'subject', 'id');
      }

}
