<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Payment as PaymentModel;

class MemberTrackController extends Controller
{
    public function byAdmin()
    {
        $memberTracks = PaymentModel::where('created_by_type', 'ADMIN')->get();
        return view("admin.membertrack.admin", compact(
            'memberTracks'
        ));
    }

    public function byUser() {
        $memberTracks = PaymentModel::where('created_by_type', 'USER')->get();
        return view("admin.membertrack.user", compact(
            'memberTracks'
        ));
    }

}
