<?php

namespace App\Models\Program;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Events extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'category',
        'start_date',
        'end_date',
        'start_time',
        'end_time',
        'message',
        'location',
        'event_for_students',
        'event_for_teachers',
        'event_for_parents',
        'event_for_everyone',
    ];
}
