<?php

namespace App\Models\Student;
use App\Models\Student\StudentPersonalInfo;
use App\Models\Teacher\Route;
use App\Models\Teacher\Vehicle;
use App\Models\Teacher\MonthlyFair;
use App\Models\Teacher\PickupPoint;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class StudentTransport extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'route_id',
        'vehicle_id',
        'monthly_fair_id',
        'pickup_point_id',
    ];

    public function student()
    {
        return $this->belongsTo(StudentPersonalInfo::class, 'student_id');
    }

    public function route()
    {
        return $this->belongsTo(Route::class, 'route_id');
    }

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class, 'vehicle_id');
    }

    public function monthlyFair()
    {
        return $this->belongsTo(MonthlyFair::class, 'monthly_fair_id');
    }

    public function pickupPoint()
    {
        return $this->belongsTo(PickupPoint::class, 'pickup_point_id');
    }
}



