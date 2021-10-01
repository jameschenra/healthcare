<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $table = "payments";

    protected $fillable = [
        'owner_id', 'user_id', 'owner_name', 'user_name', 'relationship', 'plan', 'price', 'payment_type',
        'expires_in', 'status', 'created_by', 'created_by_type'
    ];
}
