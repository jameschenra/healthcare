<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Enums\ExpireType;

class ExpireMail extends Mailable
{
    use Queueable, SerializesModels;

    public $membership;
    public $expireType;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($membership, $expireType)
    {
        $this->membership = $membership;
        $this->expireType = $expireType;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $email = '';
        if ($this->membership->member_id == null) {
            $email = $this->membership->owner->email;
        } else {
            $email = $this->membership->user->email;
        }

        $relationship = $this->membership->member_id != null ? $this->membership->user->relationship : 'Primary';
        
        $expireSentence = 'Membership(' . $this->membership->membership_number . ')'
            . 'for ' . $relationship . ' ';

        switch($this->expireType) {
            case ExpireType::EXPIRE_THIRTY_DAYS:
                $expireSentence .= 'will be expired in 30 days.';
                break;
            case ExpireType::EXPIRE_FOURTEEN_DAYS:
                $expireSentence .= 'will be expired in 14 days.';
                break;
            case ExpireType::EXPIRE_SEVEN_DAYS:
                $expireSentence .= 'will be expired in 7 days.';
                break;
            case ExpireType::EXPIRE_TWO_DAYS:
                $expireSentence .= 'will be expired in 2 days.';
                break;
            case ExpireType::EXPIRE_END:
                $expireSentence .= 'has been expired.';
                break;
            default:
                $expireSentence .= 'has been expired.';
        }

        return $this->subject('Health service payment expire.')
            ->markdown('Email.SendExpire')->with([
            'expireSentence' => $expireSentence,
            'email' => $email,
            'name' => $this->membership->user->first_name
        ]);
    }
}
