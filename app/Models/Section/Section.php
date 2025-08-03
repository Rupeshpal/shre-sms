<?php

namespace App\Models\Section;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    use HasFactory;

    public $incrementing = false; // since 'id' is varchar
    protected $primaryKey = 'id';

    protected $fillable = [

        'section_name',
        'status',
    ];
}