<?php

namespace App\Models\Teacher;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeacherSocialLink extends Model
{
    use HasFactory;

    protected $fillable = [
        'teacher_id',
        'facebook',
        'instagram',
        'twitter',
        'linkedin',
        'youtube',
        'tiktok',
    ];

    public function teacher()
    {
        return $this->belongsTo(\App\Models\Teacher\Teacher::class);
    }
}
