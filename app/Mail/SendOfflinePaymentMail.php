<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendOfflinePaymentMail extends Mailable
{
    use Queueable, SerializesModels;

    public $memberships;
    public $toName;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($toName, $memberships)
    {
        $this->memberships = $memberships;
        $this->toName = $toName;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Health service payment success.')
            ->markdown('Email.SendOfflinePayment')->with([
                'name' => $this->toName,
                'memberships' => $this->memberships
        ]);
    }
}
