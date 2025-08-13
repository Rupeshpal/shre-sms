<?php

namespace App\Models\Student;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentGuardianInfo extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';
    protected $table = 'student_guardian_info';
    protected $keyType = 'int';
    public $incrementing = true;


    protected $fillable = [
        'id',
        'student_id',
        'name',
        'email',
        'phone_number',
        'occupation',
        'temporary_address',
        'permanent_address',
        'nationality',
    ];

    public function student()
    {
        return $this->belongsTo(\App\Models\Student\StudentPersonalInfo::class, 'student_id');
    }
}
