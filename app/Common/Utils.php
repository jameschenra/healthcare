<?php 

namespace App\Common;

use Carbon\Carbon;

class Utils {
    public static function checkAdult($birthday) {
        if ($birthday) {
            $birthYear = Carbon::parse($birthday)->year;
            if($birthYear <= (now()->year - 18)) {
                return true;
            }
        }

        return false;
    }
}