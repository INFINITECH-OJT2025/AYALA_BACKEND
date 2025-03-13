<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PropertyInquiry extends Model {
    use HasFactory;

    protected $fillable = ['property_id', 'last_name', 'first_name', 'email', 'phone', 'message', 'status',];

    public function property() {
        return $this->belongsTo(Property::class);
    }
}
