<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FAQ extends Model
{
    use HasFactory;

    // Define the table name if it's not the plural form of the model
    protected $table = 'faqs';

    // Define the fillable fields for mass assignment
    protected $fillable = ['question', 'answer'];

    // Define any relationships (if applicable) or other model configurations
}
