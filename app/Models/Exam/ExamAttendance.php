<?php
namespace App\Models\Exam;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamAttendance extends Model
{
    use HasFactory;

    protected $table = 'exam_attendance';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'rollNo',
        'class',
        'section',
        'student',
        'science',
        'chemistry',
        'math',
        'social',
    ];
}
