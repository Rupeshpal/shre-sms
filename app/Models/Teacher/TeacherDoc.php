<?php

namespace App\Models\Teacher;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class TeacherDoc extends Model
{
    use HasFactory;
    protected $table="teacher_documents";

    protected $casts = [
        'teacher_id' => 'integer',
    ];

    protected $fillable = [
        'teacher_id',
        'joining_letter',
        'experience_certificate',
        'character_certificate',
        'main_sheets',
        'medical_condition_file',
        'medical_status',
        'allergies',
        'medication',
    ];

    protected $appends = ['joiningLetter', 'experienceCertificate', 'characterCertificate', 'mainSheets', 'medicalConditionFile', 'medicalStatus'];

    public function getJoiningLetterAttribute()
    {
        return $this->attributes['joining_letter'];
    }

    public function getExperienceCertificateAttribute()
    {
        return $this->attributes['experience_certificate'];
    }

    public function getCharacterCertificateAttribute()
    {
        return $this->attributes['character_certificate'];
    }

    public function getMainSheetsAttribute()
    {
        return $this->attributes['main_sheets'];
    }

    public function getMedicalConditionFileAttribute()
    {
        return $this->attributes['medical_condition_file'];
    }

    public function getMedicalStatusAttribute()
    {
        return $this->attributes['medical_status'];
    }
    
    public function teacher()
    {
        return $this->belongsTo(\App\Models\Teacher\Teacher::class, 'teacher_id');
    }
    
}
