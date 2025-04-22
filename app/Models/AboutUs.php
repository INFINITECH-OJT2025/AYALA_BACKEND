<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AboutUs extends Model
{
    use HasFactory;

    protected $table = 'about_us';

    protected $fillable = [
        'hero_title',
        'hero_subtitle',
        'hero_image',
        'mission_title',
        'mission_description',
        'vision_title',
        'vision_description',
        'history', 
    ];

    protected $casts = [
        'history' => 'array', 
    ];
}
