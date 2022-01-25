<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewOrderReviewToUser extends Mailable
{
    use Queueable, SerializesModels;

    public $firstname;
    public $order;
    public $orderdate;
    public $item;
    public $price;
    public $currency;
    public $symbol;
    public $meeting;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($firstname, $order, $orderdate, $item, $price, $currency, $symbol,$meeting=null)
    {
        $this->firstname = $firstname;
        $this->order = $order;
        $this->orderdate = $orderdate;
        $this->item = $item;
        $this->price = $price;
        $this->currency = $currency;
        $this->symbol = $symbol;
        $this->meeting=$meeting;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('frontend.mail.new_order_review_user')
        ->subject("Agora.community: your order summary");
    }
}
