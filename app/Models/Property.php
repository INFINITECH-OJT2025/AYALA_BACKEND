<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Property extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone_number',
        'type_of_listing',
        'property_name',
        'location',
        'price',
        'square_meter',
        'floor_number',
        'unit_type',
        'unit_status',
        'other_details',
        'description',
        'property_image',
        'pool_area',
        'guest_suite',
        'underground_parking',
        'pet_friendly_facilities',
        'balcony_terrace',
        'club_house',
        'parking',
        'gym_fitness_center',
        'elevator',
        'concierge_services',
        'security',
        'status',
    ];

    protected $casts = [
        'other_details' => 'json',
        'property_image' => 'array',
        'other_details' => 'array',
        'pool_area' => 'boolean',
        'guest_suite' => 'boolean',
        'underground_parking' => 'boolean',
        'pet_friendly_facilities' => 'boolean',
        'balcony_terrace' => 'boolean',
        'club_house' => 'boolean',
        'gym_fitness_center' => 'boolean',
        'elevator' => 'boolean',
        'concierge_services' => 'boolean',
        'security' => 'boolean',
    ];

    public function views()
    {
        return $this->hasMany(PropertyView::class);
    }

    // Get the unique view count
    public function getUniqueViewsAttribute()
    {
        return $this->views()->count();
    }
}
