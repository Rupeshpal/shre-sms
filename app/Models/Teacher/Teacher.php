<?php

namespace App\Models\Teacher;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Teacher\TeacherAssignment;

class Teacher extends Model
{
    use HasFactory;

    protected $table = 'teachers';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $casts = [
        'date_of_birth' => 'date',
        'date_of_joining' => 'date',
        'status' => 'boolean',
    ];

    protected $fillable = [
        'teacher_code', 'first_name', 'last_name', 'email', 'primary_contact',
        'gender', 'blood_group', 'date_of_birth', 'date_of_joining', 'marital_status',
        'qualification', 'work_experience', 'father_name', 'mother_name',
        'house', 'mother_tongue', 'status'
    ];

    public function assignments()
    {
        return $this->hasMany(TeacherAssignment::class, 'teacher_id', 'id');
    }
}
