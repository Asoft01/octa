<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewOrderReviewToAgora extends Mailable
{
    use Queueable, SerializesModels;

    public $firstname;
    public $order;
    public $orderdate;
    public $item;
    public $price;
    public $duedate;
    public $video;
    public $note;
    public $currency;
    public $symbol;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($firstname, $order, $orderdate, $item, $price, $duedate, $video, $note, $currency, $symbol)
    {
        $this->firstname = $firstname;
        $this->order = $order;
        $this->orderdate = $orderdate;
        $this->item = $item;
        $this->price = $price;
        $this->duedate = $duedate;
        $this->video = $video;
        $this->note = $note;
        $this->currency = $currency;
        $this->symbol = $symbol;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('frontend.mail.new_order_review_agora')
        ->subject("Agora.community: new order");
    }
}
