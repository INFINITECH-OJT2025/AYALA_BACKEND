<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactDetail extends Model
{
    use HasFactory;

    protected $table = 'contact_details';

    protected $fillable = [
        'phones',
        'email',
        'social_media',
        'location',
    ];

    protected $casts = [
        'phones' => 'array',
        'social_media' => 'array',
    ];
}
