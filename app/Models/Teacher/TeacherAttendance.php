<?php

namespace App\Models\Teacher;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeacherAttendance extends Model
{
    use HasFactory;
    protected $table = 'teacher_attendances';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $casts = [
        'teacher_id' => 'integer',
        'attendance_percent' => 'string',
    ];

    protected $fillable = [
        'teacher_id',
        'attendance_percent',
    ];


    public function teacher()
    {
        return $this->belongsTo('App\Models\Teacher\Teacher', 'teacher_id');
    }
}
