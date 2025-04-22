<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class JobPostedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $job;

    public function __construct($job)
    {
        $this->job = $job;
    }

    public function build()
    {
        return $this->subject('New Job Opportunity Available!')
            ->view('emails.job_posted')
            ->with(['job' => $this->job]);
    }
}
