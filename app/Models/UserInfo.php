<?php

namespace App\Models;

use App\User;
use App\Models\Country;
use App\Models\Membership;
use Illuminate\Database\Eloquent\Model;

class UserInfo extends Model
{
    protected $table = "user_infos";

    protected $fillable = [
        'user_id', 'member_id', 'first_name', 'last_name', 'phone', 'birthday', 'gender', 'address',
        'city', 'country_id', 'region', 'photo'
    ];

    public function user() {
        return $this->belongsTo(
            User::class,
            'user_id',
            'id'
        );
    }

    public function country() {
        return $this->belongsTo(
            Country::class,
            'country_id',
            'id'
        );
    }
}
