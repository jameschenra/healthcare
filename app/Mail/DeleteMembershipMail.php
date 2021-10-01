<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class DeleteMembershipMail extends Mailable
{
    use Queueable, SerializesModels;

    public $name;
    public $membership_number;
    public $expires_in;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($name, $membership_number, $expires_in)
    {
        $this->name = $name;
        $this->membership_number = $membership_number;
        $this->expires_in = $expires_in;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Health service delete member.')
            ->markdown('Email.DeleteMembership')->with([
                'name' => $this->name,
                'membership_number' => $this->membership_number,
                'expires_in' => $this->expires_in,
        ]);
    }
}
