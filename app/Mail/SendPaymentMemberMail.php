<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendPaymentMemberMail extends Mailable
{
    use Queueable, SerializesModels;

    public $membership;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($membership)
    {
        $this->membership = $membership;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Health service payment success.')
            ->markdown('Email.SendPaymentMember')->with([
            'membership' => $this->membership
        ]);
    }
}
