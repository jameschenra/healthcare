<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Testmonial extends Model
{
    protected $table = "testmonials";

    protected $fillable = [
        'owner', 'content', 'description', 'image'
    ];

    protected $hidden = [];
}
