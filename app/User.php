<?php

namespace App;

use App\Models\UserInfo;
use App\Notifications\CustomResetPassword;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Membership;
use App\Models\MembershipPending;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'email', 'password', 'relationship', 'primary_id', 'is_adult', 'membership_id', 'member_number'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        // 'email_verified_at' => 'datetime',
    ];

    public function userInfo()
    {
        return $this->hasOne(
            UserInfo::class,
            'user_id',
            'id'
        );
    }

    public function owner()
    {
        return $this->belongsTo(
            User::class,
            'primary_id',
            'id'
        );
    }

    public function members()
    {
        return $this->hasMany(
            User::class,
            'primary_id',
            'id'
        );
    }

    public function membership() {
        return $this->hasOne(
            Membership::class,
            'id',
            'membership_id'
        );
    }

    public function membership_pending() {
        return $this->hasOne(
            MembershipPending::class,
            'user_id',
            'id'
        );
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new CustomResetPassword($token));
    }
}
