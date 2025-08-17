<?php

namespace App\Models\Teacher;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PickupPoint extends Model
{
    use HasFactory;

    protected $table = 'pickup_points';
    protected $fillable = ['name'];

    public function teacherTransports()
    {
        return $this->hasMany(TeacherTransport::class, 'pickup_point_id');
    }
}
