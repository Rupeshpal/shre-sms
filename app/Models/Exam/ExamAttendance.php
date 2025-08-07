<?php
namespace App\Models\Exam;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Classes\Classes;
use App\Models\Section\Section;
use App\Models\Subject\Subject;
use App\Models\Student\StudentPersonalInfo;
class ExamAttendance extends Model
{
    use HasFactory;
    protected $table = 'exam_attendance';
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $keyType = 'int';


protected $fillable = [
    'id',
    'roll_no',
    'class_id',
    'section_id',
    'student_id',
    'science',
    'chemistry',
    'math',
    'social',
];



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
       public function student()
    {
        return $this->belongsTo(StudentPersonalInfo::class, 'student_id', 'id');
    }
}
