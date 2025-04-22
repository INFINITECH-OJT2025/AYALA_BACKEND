<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class FeaturedNewsMail extends Mailable
{
    use Queueable, SerializesModels;

    public $news;

    public function __construct($news)
    {
        $this->news = $news;
    }

    public function build()
    {
        return $this->subject('New Featured News Update')
            ->view('emails.featured_news')
            ->with(['news' => $this->news]);
    }
}
