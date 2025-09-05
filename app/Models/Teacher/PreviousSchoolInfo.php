<?php
namespace App\Models\Teacher;

use Illuminate\Database\Eloquent\Model;

class PreviousSchoolInfo extends Model
{
    protected $table = 'previous_school_infos';
    protected $casts = [
        'teacher_id' => 'integer',
    ];
    protected $fillable = [
        'teacher_id',
        'school_name',
        'location',
        'affiliation_board',
        'school_contact_number',
    ];
}
