<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ApplicantNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $subjectText;
    public $messageContent;
    public $applicantId;
    public $frontendUrl;
    public $applicantEmail;
    public $status; // ✅ Add status (approved/rejected)
    public $newSchedule; // ✅ Add new schedule (if approved)

    public function __construct($subjectText, $messageContent, $applicantId, $frontendUrl, $applicantEmail, $status, $newSchedule = null)
    {
        $this->subjectText = $subjectText;
        $this->messageContent = $messageContent;
        $this->applicantId = $applicantId;
        $this->frontendUrl = $frontendUrl;
        $this->frontendUrl = rtrim($frontendUrl, '/'); // Ensure no trailing slash
        $this->applicantEmail = $applicantEmail;
        $this->status = $status; // ✅ Store approval/rejection status
        $this->newSchedule = $newSchedule; // ✅ Store new schedule if approved
    }

    public function build()
    {
        return $this->subject($this->subjectText)
                    ->view('emails.notification')
                    ->with([
                        'subjectText' => $this->subjectText,
                        'messageContent' => $this->messageContent,
                        'applicantId' => $this->applicantId,
                        'frontendUrl' => $this->frontendUrl,
                        'applicantEmail' => $this->applicantEmail,
                        'status' => $this->status, // ✅ Pass status
                        'newSchedule' => $this->newSchedule, // ✅ Pass new schedule if available
                    ]);
    }
}
