<?php

namespace App\Models\Teacher;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeacherTransport extends Model
{
    use HasFactory;

    protected $fillable = [
        'teacher_id',
        'route_id',
        'vehicle_id',
        'monthly_fair_id',
        'pickup_point_id',
    ];

    public function teacher()
    {
        return $this->belongsTo(Teacher::class, 'teacher_id');
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
