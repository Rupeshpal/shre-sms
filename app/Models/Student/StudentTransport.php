<?php

namespace App\Models\Student;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentTransport extends Model
{
    use HasFactory;

    protected $table = 'student_transport';
    protected $primaryKey = 'id';
    protected $fillable = [
        'student_id',
        'route',
        'vehicle_number',
        'monthly_fare',
        'pickup_point',
    ];

    public function student()
    {
        return $this->belongsTo(StudentPersonalInfo::class, 'student_id');
    }
}
