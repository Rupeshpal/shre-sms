<?php

namespace App\Models\Program;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventCalendar extends Model
{
    use HasFactory;

    protected $table = 'event_calendar';

    protected $fillable = [
        'title',
        'description',
        'type',
        'color',
        'start_datetime',
        'end_datetime',
    ];
}
