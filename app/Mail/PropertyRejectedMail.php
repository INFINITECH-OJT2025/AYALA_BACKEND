<?php

namespace App\Mail;

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Property;

class PropertyRejectedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $property;
    public $reason;

    public function __construct(Property $property, $reason)
    {
        $this->property = $property;
        $this->reason = $reason;
    }

    public function build()
    {
        return $this->subject("Your Property Listing was Rejected")
            ->view("emails.property_rejected")
            ->with([
                "property" => $this->property,
                "reason" => $this->reason,
            ]);
    }
}
