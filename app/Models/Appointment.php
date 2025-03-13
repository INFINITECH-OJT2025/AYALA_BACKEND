<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model {
    use HasFactory;

    protected $fillable = ['property_id', 'last_name', 'first_name', 'email', 'phone', 'date', 'time', 'message'];

    public function property() {
        return $this->belongsTo(Property::class);
    }
}
