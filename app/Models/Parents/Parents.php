<?php

namespace App\Models\Parents;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Parents extends Model
{
    use HasFactory;

    protected $table = 'parents';

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'parent_name',
        'gender',
        'nationality',
        'occupation',
        'primary_mobile_number',
        'alternate_contact_number',
        'email_address',
        'temporary_address',
        'permanent_address',
        'added_date',
        'image',
    ];
}