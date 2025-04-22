<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApplicantAppointment extends Model
{
    use HasFactory;

    protected $fillable = ['applicant_id', 'schedule_datetime', 'message'];

    protected $casts = [
        'schedule_datetime' => 'datetime', 
    ];

    public function applicant()
    {
        return $this->belongsTo(JobApplication::class, 'applicant_id', 'id');
    }
}
