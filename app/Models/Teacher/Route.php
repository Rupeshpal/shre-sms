<?php

namespace App\Models\Teacher;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Route extends Model
{
    use HasFactory;

    protected $table = 'routes';
    protected $fillable = ['name'];

    public function teacherTransports()
    {
        return $this->hasMany(TeacherTransport::class, 'route_id');
    }
}
