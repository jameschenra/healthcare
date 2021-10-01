<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Slide extends Model
{
    protected $table = "slides";

    protected $fillable = [
        'file_name', 'title', 'sub_title', 'order'
    ];

    protected $hidden = [];
}
