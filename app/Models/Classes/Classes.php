<?php

namespace App\Models\Classes;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Classes extends Model
{
    use HasFactory;

    public $incrementing = true;
    public $timestamps = true;


    protected $primaryKey = 'id';
    protected $casts = [
        'room_no' => 'integer',
        'capacity' => 'integer',
        'no_of_students'=>'integer',
        'no_of_subjects' =>'integer'

    ];

    protected $fillable = [
        'id',
        'class_name',
        'capacity',
        'room_no',
        'no_of_students',
        'no_of_subjects',
        'cr_name',
        'class_teacher',
        'class_status',
    ];
}
