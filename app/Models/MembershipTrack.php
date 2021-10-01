<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MembershipTrack extends Model
{
    protected $table = "membership_tracks";

    protected $fillable = [
        'owner_name', 'owner_email', 'user_name', 'user_email', 'relationship', 'plan', 'price', 'expires_in', 'created_by'
    ];

}
