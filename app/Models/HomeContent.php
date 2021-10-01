<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HomeContent extends Model
{
    protected $table = "home_contents";

    protected $fillable = [
        'type', 'title', 'content', 'image'
    ];

    protected $hidden = [];
}
