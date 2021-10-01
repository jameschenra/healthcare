<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\AdminUserRequest;

use App\Admin;
use App\Enums\UserType;

class AdminUserController extends Controller
{
    public function index()
    {
        $users = Admin::where('role_id', UserType::ADMIN)->get();
        return view("admin.admin_users.list", [
            "users" => $users
        ]);
    }

    public function create()
    {
        return view("admin.admin_users.add");
    }

    public function store(AdminUserRequest $request)
    {
        $data = $request->all();
        $data['password'] = bcrypt($data['password']);

        $user = Admin::Create($data);

        return redirect()->route("admin.adminusers.index");
    }

    public function edit($id)
    {
        $user = Admin::find($id);

        return view("admin.admin_users.edit", [
            'user' => $user
        ]);
    }

    public function update($id, AdminUserRequest $request)
    {
        $user = Admin::find($id);
        if ($user) {
            $user->email = $request->input('email');

            if ($request->input("password")){
                $user->password = bcrypt($request->input("password"));
            }
    
            $user->save();
        }
        
        return redirect()->route("admin.adminusers.index");
    }

    public function destroy($id)
    {
        $user = Admin::find($id);
        if ($user) {
            $user->delete();
        }
        
        return redirect()->route("admin.adminusers.index");
    }
}
