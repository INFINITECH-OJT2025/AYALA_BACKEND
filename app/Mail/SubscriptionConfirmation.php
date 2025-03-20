<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SubscriptionConfirmation extends Mailable {
    use Queueable, SerializesModels;

    public function __construct() {}

    public function build() {
        return $this->subject('Subscription Confirmed!')
                    ->view('emails.subscription_confirmation');
    }
}
