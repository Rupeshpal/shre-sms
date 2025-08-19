<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AcademicYear extends Model
{
    use HasFactory;
    protected $table = 'academic_years';
    protected $casts = [
        'start_date' => 'datetime',
        'status' => 'boolean',
        'current_academic_year' => 'boolean',
        'semester_count'=>'integer',
        'exam_count'  =>'integer',
        'end_date' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
    

    protected $fillable = [
        'academic_year',
        'start_date',
        'end_date',
        'semester_count',
        'exam_count',
        'status',
        'current_academic_year',
    ];
}