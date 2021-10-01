<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Discount;

class DiscountController extends Controller
{
    public function index()
    {
        $discounts = Discount::all();
        return view("admin.discounts.list", compact(
            'discounts'
        ));
    }

    public function create() {
        return view('admin.discounts.add');
    }

    public function store(Request $request) {
        $this->validate($request, [
            'name' => 'required',
            'value' => 'required'
        ]);

        Discount::create($request->all());

        return redirect()->route('admin.discounts.index');
    }

    public function edit($id)
    {
        $discount = Discount::find($id);

        return view("admin.discounts.edit", compact(
            'discount'
        ));
    }

    public function update($id, Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'value' => 'required'
        ]);

        $discount = Discount::find($id);
        $discount->update($request->all());

        return redirect()->route("admin.discounts.index");
    }

    public function destroy($id) {
        $discount = Discount::find($id);
        if ($discount) {
            $discount->delete();
        }

        return redirect()->back();
    }
}
