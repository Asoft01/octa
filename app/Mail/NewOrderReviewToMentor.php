<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewOrderReviewToMentor extends Mailable
{
    use Queueable, SerializesModels;

    public $firstname;
    public $order;
    public $orderdate;
    public $item;
    public $duedate;
    public $video;
    public $note;
    public $meeting;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($firstname, $order, $orderdate, $item, $duedate, $video, $note,$meeting=null)
    {
        $this->firstname = $firstname;
        $this->order = $order;
        $this->orderdate = $orderdate;
        $this->item = $item;
        $this->duedate = $duedate;
        $this->video = $video;
        $this->note = $note;
        $this->meeting=$meeting;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('frontend.mail.new_order_review_mentor')
        ->subject("Agora.community: you have a new order");
    }
}

