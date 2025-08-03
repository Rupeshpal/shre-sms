<?php

namespace App\Models\Student;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentDocument extends Model
{
    use HasFactory;
    protected $table = 'student_documents';
    protected $primaryKey = 'id';


    protected $fillable = [
        'student_id',
        'transfer_certificate',
        'birth_certificate',
        'character_certificate',
        'transcripts',
        'medical_condition',
        'allergies',
        'medication',
        'medical_document'
    ];

    public function student()
    {
        return $this->belongsTo(\App\Models\Student\StudentPersonalInfo::class, 'student_id');
    }
}
