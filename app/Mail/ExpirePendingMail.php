<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Enums\ExpirePendingType;

class ExpirePendingMail extends Mailable
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

        $expireSentence = 'Pending membership(' . $this->membership->membership_number . ')'
            . ' for ' . $relationship . ' ';

        switch($this->expireType) {
            case ExpirePendingType::EXPIRE_TWO_DAYS:
                $expireSentence .= 'past 2 days.';
                break;
            case ExpirePendingType::EXPIRE_SEVEN_DAYS:
                $expireSentence .= 'past 7 days.';
                break;
            case ExpirePendingType::EXPIRE_FOURTEEN_DAYS:
                $expireSentence .= 'past 14 days.';
                break;
            case ExpirePendingType::EXPIRE_END:
                $expireSentence .= 'will be deactivated.';
                break;
            default:
                $expireSentence .= 'will be deactivated.';
        }

        return $this->subject('Pending membership past days.')
            ->markdown('Email.SendPendingExpire')->with([
            'expireSentence' => $expireSentence,
            'email' => $email,
            'name' => $this->membership->user->first_name
        ]);
    }
}
