<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewPropertyMail extends Mailable {
    use Queueable, SerializesModels;

    public $property;

    public function __construct($property) {
        $this->property = $property;
    }

    public function build() {
        return $this->subject('New Property Listing Available!')
                    ->view('emails.new_property')
                    ->with(['property' => $this->property]);
    }
}
