<?php

namespace App\Models\Student;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PreviousSchoolDetail extends Model
{
    use HasFactory;
    protected $table = 'previous_school_details';
    protected $primaryKey = 'id';
    public $timestamps = true;
    protected $keyType = 'int';
    

    protected $fillable = [
        'id',
        'student_id',
        'school_name',
        'location',
        'affiliation_board',
        'school_contact_number',
    ];

    public function student()
    {
        return $this->belongsTo(StudentPersonalInfo::class, 'student_id');
    }
}
