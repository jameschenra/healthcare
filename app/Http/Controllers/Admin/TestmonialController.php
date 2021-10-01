<?php

namespace App\Http\Controllers\Admin;

use App\Models\Testmonial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\TestmonialRequest;

class TestmonialController extends Controller
{
    public function index()
    {
        $testmonials = Testmonial::get();
        return view("admin.contents.testmonials.list", compact(
            'testmonials'
        ));
    }

    public function create()
    {
        return view("admin.contents.testmonials.add");
    }

    public function store(TestmonialRequest $request)
    {
        $fileName = '';

        $imageFile = $request->image;
        if ($imageFile) {
            $fileName = time() . '.' . $imageFile->getClientOriginalExtension();
            $imageFile->move(public_path('files/testmonials'), $fileName);
        }

        Testmonial::Create([
            'owner' => $request->input('owner'),
            'content' => $request->input('content'),
            'description' => $request->input('description'),
            'image' => $fileName,
        ]);

        return redirect()->route("admin.contents.testmonial.index");
    }

    public function edit($id)
    {
        $testmonial = Testmonial::find($id);

        return view("admin.contents.testmonials.edit", compact(
            'testmonial'
        ));
    }

    public function update($id, TestmonialRequest $request)
    {
        $testmonial = Testmonial::find($id);
        $testmonial->owner = $request->input("owner");
        $testmonial->content = $request->input("content");
        $testmonial->description = $request->input("description");

        $imageFile = $request->image;
        if ($imageFile) {
            $oldFileName = public_path('files/testmonials/' . $testmonial->image);
            if (file_exists($oldFileName)) {
                \File::delete($oldFileName);
            }

            $fileName = time() . '.' . $imageFile->getClientOriginalExtension();
            $imageFile->move(public_path('files/testmonials'), $fileName);
            $testmonial->image = $fileName;
        }

        $testmonial->save();
        return redirect()->route("admin.contents.testmonial.index");
    }

    public function destroy($id)
    {
        $testmonial = Testmonial::find($id);

        $oldFileName = public_path('files/testmonials/' . $testmonial->image);
        if (file_exists($oldFileName)) {
            \File::delete($oldFileName);
        }
        
        $testmonial->delete();

        return redirect()->route("admin.contents.testmonial.index");
    }
}
