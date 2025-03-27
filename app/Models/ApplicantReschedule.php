<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApplicantReschedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'applicant_id',
        'email',
        'new_schedule',
        'applicant_message',
        'file_path',
        'status',
    ];

    protected $casts = [
        'new_schedule' => 'datetime', // ✅ Ensure new_schedule is treated as a datetime
    ];

    public function applicant()
    {
        return $this->belongsTo(JobApplication::class, 'applicant_id', 'id');
    }
}

