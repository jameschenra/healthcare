<?php

namespace App\Http\Controllers\Admin;

use App\Models\MemberPlanType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\MemberPlanTypeRequest;

class MemberPlanTypeController extends Controller
{
    public function index()
    {
        $memberPlanTypes = MemberPlanType::all();
        return view("admin.memberplantypes.list", compact(
            'memberPlanTypes'
        ));
    }

    public function edit($id)
    {
        $plan = MemberPlanType::find($id);

        return view("admin.memberplantypes.edit", compact(
            'plan'
        ));
    }

    public function update($id, MemberPlanTypeRequest $request)
    {
        $plan = MemberPlanType::find($id);
        $plan->adult_price = $request->input('adult_price');
        $plan->child_price = $request->input('child_price');
        $plan->desc_first = $request->input('desc_first');
        $plan->desc_second = $request->input('desc_second');
        $plan->desc_third = $request->input('desc_third');
        $plan->save();

        return redirect()->route("admin.memberplantypes");
    }
}
