<?php

class Utils
{
    const GENDERS = [
        'Male',
        'Female',
        'Other'
    ];

    const RELATIONSHIPS = [
        'Wife',
        'Husband',
        'Son',
        'Daughter',
        'Parent',
        'Other'
    ];

    const MEMBERSTATUS = [
        'Draft',
        'Pending',
        'Purchase',
        'Expired'
    ];

    public static function checkMenuActive($route) {
        if(strpos(Route::currentRouteName(),$route) === false) {
            return '';
        }
        return 'class=active';
    }

    public static function checkMenuOpen($route) {
        if(strpos(Route::currentRouteName(),$route) === false) {
            return '';
        }
        return 'active menu-open';
    }

    public static function isPrimary() {
        $isPrimary = false;
        $user = Auth::user();
        if ($user->relationship == 'Primary') {
            $isPrimary = true;
        }

        return $isPrimary;
    }
}
