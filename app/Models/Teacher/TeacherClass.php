<?php

namespace App\Models\Teacher;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeacherClass extends Model
{
    use HasFactory;

    protected $fillable = [
        'teacher_id',
        'class',
        'section',
    ];

    public function teacher()
    {
        return $this->belongsTo(\App\Models\Teacher\Teacher::class);
    }
}
