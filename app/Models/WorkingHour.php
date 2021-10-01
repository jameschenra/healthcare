<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class WorkingHour extends Model
{
    protected $table = "working_hours";

    protected $fillable = [
        'week_day', 'start', 'end', 'open_status'
    ];

    protected $hidden = [];

    public function getStartAttribute($value) {
        $date = Carbon::createFromFormat('H:i:s', $value)->format('H:i A');
        return $date;
    }

    public function getEndAttribute($value) {
        $date = Carbon::createFromFormat('H:i:s', $value)->format('H:i A');
        return $date;
    }
}
