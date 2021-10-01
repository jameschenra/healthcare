<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class PendingCompleteMail extends Mailable
{
    use Queueable, SerializesModels;

    public $membership;
    public $toName;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($toName, $membership)
    {
        $this->membership = $membership;
        $this->toName = $toName;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Health service offline payment success.')
            ->markdown('Email.PendingComplete')->with([
            'name' => $this->toName,
            'membership' => $this->membership
        ]);
    }
}
