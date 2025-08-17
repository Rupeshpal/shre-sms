<?php

namespace App\Models\Teacher;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    use HasFactory;

    protected $table = 'vehicles';
    protected $fillable = ['number'];

    public function teacherTransports()
    {
        return $this->hasMany(TeacherTransport::class, 'vehicle_id');
    }
}
