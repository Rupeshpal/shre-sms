<?php

namespace App\Models\Teacher;

use Illuminate\Database\Eloquent\Model;

class PreviousTeacherInfo extends Model
{
    protected $table='previous_school_infos';
    protected $casts=[

    ];

    protected $fillable=[
        'teacher_id',
        'school_name',
        'location',
        'affiliation_board',
        'school_contact_number'
    ];
}
