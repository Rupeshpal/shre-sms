<?php

namespace App\Models\Program;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
class Notice extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'message',
        'added_id',
        'attachment',
        'notice_for_students',
        'notice_for_teachers',
        'notice_for_parents',
        'notice_for_everyone',
    ];

    public function addedBy()
    {
        return $this->belongsTo(User::class, 'added_id');
    }
}
