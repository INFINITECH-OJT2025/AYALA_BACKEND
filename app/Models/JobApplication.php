<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobApplication extends Model
{
    use HasFactory;

    protected $fillable = [
        'job_title',
        'first_name',
        'last_name',
        'email',
        'phone',
        'address',
        'resume_path',
        'status',
        'schedule_date',
    ];

    public function reschedule()
    {
        return $this->hasOne(ApplicantReschedule::class, 'applicant_id', 'id');
    }

    public function appointment()
    {
        return $this->hasOne(ApplicantAppointment::class, 'applicant_id', 'id');
    }
}
