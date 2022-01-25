<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NotifyReviewIsPublished extends Mailable
{
    use Queueable, SerializesModels;

    public $firstname;
    public $delivery_id;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($firstname, $delivery_id)
    {
        $this->firstname = $firstname;
        $this->delivery_id = $delivery_id;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('frontend.mail.notify_review_published')
        ->subject("Agora.community | your review is published")
        ->from("info@agora.studio", "Agora");
    }
}
