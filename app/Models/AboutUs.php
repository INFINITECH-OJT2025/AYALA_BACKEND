<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AboutUs extends Model
{
    use HasFactory;

    protected $table = 'about_us';
    
    protected $fillable = [
        'hero_title', 'hero_subtitle', 'hero_image',
        'mission_title', 'mission_description',
        'vision_title', 'vision_description',
        'history', // ✅ Stores multiple history entries in JSON format
    ];

    protected $casts = [
        'history' => 'array', // ✅ Ensures history is always treated as an array
    ];
}
