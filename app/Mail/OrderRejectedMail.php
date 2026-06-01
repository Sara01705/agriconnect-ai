<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderRejectedMail extends Mailable
{
    use SerializesModels;

    public $req;

    public function __construct($req)
    {
        $this->req = $req;
    }

    public function build()
    {
        return $this->subject('Order Rejected - AgriConnect')
                    ->view('emails.order_rejected');
    }
}