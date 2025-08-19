<?php

namespace App\Models\Student;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Enums\StudentStatusEnum;

class StudentPersonalInfo extends Model
{
    use HasFactory;

    protected $table = 'student_personal_info';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $casts = [
        'admission_date' => 'datetime',
        'date_of_birth' => 'date',
        'status' => StudentStatusEnum::class,
    ];

    protected $fillable = [
        'academic_year_id',
        'admission_number',
        'admission_date',
        'roll_no',
        'status',
        'first_name',
        'last_name',
        'class_id',
        'section_id',
        'gender',
        'date_of_birth',
        'blood_group',
        'house',
        'mother_tongue',
        'contact_number',
        'email',
    ];

    public function class()
    {
        return $this->belongsTo('App\Models\Classes\Classes', 'class_id');
    }
    public function academic()
    {
        return $this->belongsTo('App\Models\AcademicYear', 'academic_year_id');
    }
    public function section()
    {
        return $this->belongsTo('App\Models\Section\Section', 'section_id');
    }
}
