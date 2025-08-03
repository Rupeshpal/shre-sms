<?php
namespace App\Models\Student;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class StudentRelation extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'relation',
        'name',
        'email',
        'phone_number',
        'occupation',
        'temporary_address',
        'permanent_address',
        'nationality',
        'monthly_income',
        'document',
    ];

    public function student()
    {
        return $this->belongsTo(StudentPersonalInfo::class, 'student_id');
    }
}