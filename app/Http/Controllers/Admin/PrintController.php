<?php

namespace App\Http\Controllers\Admin;

use App\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PrintController extends Controller
{
    public function edit($id)
    {
        $user = User::find($id);
        if ($user && $user->membership) {
            return view('admin.print.edit', compact(
                    'user'
                )
            );
        } else {
            return redirect()->back();
        }
    }

    public function uploadPhoto(Request $request) {

        $userId = $request->input('user_id');
        $imageFile = $request->img_file;

        $user = User::find($userId);
        if ($user) {
            $userInfo = $user->userInfo;

            $oldFileName = public_path('files/users/' . $userId . '/' . $userInfo->photo);
            if (file_exists($oldFileName)) {
                \File::delete($oldFileName);
            }

            if ($imageFile) {
                $fileName = time() . '.' . $imageFile->getClientOriginalExtension();
                $imageFile->move(public_path('files/users/' . $userId), $fileName);
            }
            
            $userInfo->photo = $fileName;
            $userInfo->save();
        }

        return redirect()->back();
    }

    public function showPrint($userId) {
        $user = User::find($userId);
        $membership_number = $user->membership->membership_number;

        $userFolder = public_path('files/users/' . $userId);
        if (!file_exists($userFolder)) {
            \File::makeDirectory($userFolder);
        }

        $qrcodePath = public_path('files/users/' . $userId . '/qrcode.png');

        if (!file_exists($qrcodePath)) {
            \QrCode::format('png')
                ->size(100)
                ->generate('member id:' . $membership_number, $qrcodePath);
        }

        return view('admin.print.card', compact('user'));
    }
}
