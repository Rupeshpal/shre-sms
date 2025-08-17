<?php

namespace App\Models\Teacher;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Teacher\Teacher;
class TeacherAddress extends Model
{

    protected $table = 'teacher_addresses';
    use HasFactory;

    protected $fillable = [
        'teacher_id',
        'permanent_street',
        'permanent_city',
        'permanent_state',
        'permanent_country',
        'is_temp_same_as_perm',
        'temporary_street',
        'temporary_city',
        'temporary_state',
        'temporary_country',
    ];

    public function teacher()
    {
        return $this->belongsTo(Teacher::class, 'teacher_id');
    }
}
