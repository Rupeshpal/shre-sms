<?php
namespace App\Models\Student;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Guardian extends Model
{
    use HasFactory;

    protected $table = 'guardians';

    protected $fillable = [
        'student_id',
        'relation_type',
        'name',
        'email',
        'phone_number',
        'occupation',
        'temporary_address',
        'permanent_address',
        'nationality',
        'monthly_income',
        'photo_path',
    ];

    protected $casts = [
        'monthly_income' => 'integer',
        'student_id'    =>'integer',
    ];

    /**
     * Each guardian belongs to a student
     */
    public function student()
    {
        return $this->belongsTo(StudentPersonalInfo::class, 'student_id');
    }
}
