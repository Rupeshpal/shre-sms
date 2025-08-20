<?php
namespace App\Models\Teacher;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class TeacherAssignment extends Model
{
    use HasFactory;
    protected $table = 'teacher_assignments';

    protected  $casts=[
        'teacher_id' =>'integer',
        'subject_id' =>'integer',
        'class_id' => 'integer',
        'section_id' =>'integer',
    ];

    protected $fillable = [
        'teacher_id',
        'subject_id',
        'class_id',
        'section_id',
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
