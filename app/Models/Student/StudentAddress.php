<?php

namespace App\Models\Student;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentAddress extends Model
{
    use HasFactory;

    protected $table = 'student_address';
    protected $primaryKey = 'id';

    protected $fillable = [
        'student_id',
        'temp_street',
        'temp_city',
        'temp_state',
        'temp_country',
        'perm_street',
        'perm_city',
        'perm_state',
        'perm_country',
    ];

    public function student()
    {
        return $this->belongsTo(StudentPersonalInfo::class, 'student_id');
    }
}