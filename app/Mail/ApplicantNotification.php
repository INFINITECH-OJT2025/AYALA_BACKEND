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

    public function __construct($subjectText, $messageContent)
    {
        $this->subjectText = $subjectText;
        $this->messageContent = $messageContent;
    }

    public function build()
    {
        return $this->subject($this->subjectText)
                    ->view('emails.notification')
                    ->with([
                        'subjectText' => $this->subjectText,
                        'messageContent' => $this->messageContent,
                    ]);
    }
}

