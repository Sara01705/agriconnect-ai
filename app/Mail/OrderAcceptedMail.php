<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderAcceptedMail extends Mailable
{
    use SerializesModels;

    public $req;

    public function __construct($req)
    {
        $this->req = $req;
    }

    public function build()
    {
        return $this->subject('Order Accepted - AgriConnect')
                    ->view('emails.order_accepted');
    }
}