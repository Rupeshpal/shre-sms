<?php

namespace App\Models\Program;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Events extends Model
{
    use HasFactory;
    protected $table = 'events';
    protected $primaryKey = 'id';
    public $timestamps = true;
    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'event_for_students' => 'boolean',
        'event_for_teachers' => 'boolean',
        'event_for_parents' => 'boolean',
        'event_for_everyone' => 'boolean',
    ];

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
