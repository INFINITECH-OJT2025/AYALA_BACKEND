<?php

// app/Models/Testimonial.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
{
    protected $fillable = ['name', 'rating', 'experience', 'photo', 'media', 'status'];

    protected $casts = [
        'media' => 'array',
        'status' => 'boolean',
    ];
}
