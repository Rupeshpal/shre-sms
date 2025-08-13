<?php

namespace App\Models\Student;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentSibling extends Model
{
    use HasFactory;
    protected $primaryKey = 'id';
    protected $table = 'student_siblings';
    public $timestamps = true;
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'section' => 'integer',
    ];
    protected $fillable = [
        'student_id',
        'name',
        'admission_no',
        'section',
        'roll_no',
    ];

    public function student()
    {
        return $this->belongsTo(\App\Models\Student\StudentPersonalInfo::class, 'student_id');
    }
    public function section(){
        return $this->belongsTo(\App\Models\Section\Section::class, 'section');
    }
}
