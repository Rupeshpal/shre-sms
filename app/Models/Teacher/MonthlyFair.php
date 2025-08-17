<?php

namespace App\Models\Teacher;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MonthlyFair extends Model
{
    use HasFactory;

    protected $table = 'monthly_fairs';
    protected $fillable = ['amount'];

    public function teacherTransports()
    {
        return $this->hasMany(TeacherTransport::class, 'monthly_fair_id');
    }
}
