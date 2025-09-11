<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderDeliveredMail extends Mailable
{
    use Queueable, SerializesModels;

    public $customerName;
    public $orderId;
    public $orderDate;
    public $orderAmount;
    public $orderLink;

    public function __construct($customerName, $orderId, $orderDate, $orderAmount, $orderLink)
    {
        $this->customerName = $customerName;
        $this->orderId = $orderId;
        $this->orderDate = $orderDate;
        $this->orderAmount = $orderAmount;
        $this->orderLink = $orderLink;
    }

    public function build()
    {
        return $this->subject('Your Order #' . $this->orderId . ' has been Delivered 🎉')
            ->view('mail.delivery');
    }
}
