<?php

namespace App\Http\Controllers\Admin;

use App\Models\Slide;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\SlideRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class SlideController extends Controller
{
    public function index()
    {
        $slides = Slide::orderBy('order')->get();
        return view("admin.slides.list", compact(
            'slides'
        ));
    }

    public function create()
    {
        // $orders = Slide::orderBy('order')->pluck('order')->toArray();

        $maxOrder = Slide::max('order');
        $maxOrder = $maxOrder ? $maxOrder : 0;

        return view("admin.slides.add", compact(
            'maxOrder'
        ));
    }

    public function store(SlideRequest $request)
    {
        $fileName = '';

        $imageFile = $request->img_file;
        if ($imageFile) {
            $fileName = time() . '.' . $imageFile->getClientOriginalExtension();
            $imageFile->move(public_path('files/slides'), $fileName);
        }

        Slide::where('order', '>=', $request->input('order'))->increment('order');

        Slide::Create([
            'file_name' => $fileName,
            'title' => $request->input('title'),
            'sub_title' => $request->input('sub_title'),
            'order' => $request->input('order')
        ]);

        return redirect()->route("admin.slides");
    }

    public function edit($id)
    {
        $slide = Slide::find($id);

        $maxOrder = Slide::max('order');
        $maxOrder = $maxOrder ? $maxOrder : 0;

        return view("admin.slides.edit", compact(
            'slide',
            'maxOrder'
        ));
    }

    public function update($id, SlideRequest $request)
    {
        $targetOrder = $request->input("order");

        $slide = Slide::find($id);
        $slide->title = $request->input("title");
        $slide->sub_title = $request->input("sub_title");

        $imageFile = $request->img_file;
        if ($imageFile) {
            $oldFileName = public_path('files/slides/' . $slide->file_name);
            if (file_exists($oldFileName)) {
                \File::delete($oldFileName);
            }

            $fileName = time() . '.' . $imageFile->getClientOriginalExtension();
            $imageFile->move(public_path('files/slides'), $fileName);
            $slide->file_name = $fileName;
        }

        if ($slide->order != $targetOrder) {
            if ($slide->order > $targetOrder) {
                Slide::where('order', '>=', $targetOrder)
                    ->where('order', '<', $slide->order)
                    ->increment('order');
            } else {
                Slide::where('order', '<=', $targetOrder)
                    ->where('order', '>', $slide->order)
                    ->decrement('order');
            }
            
            $slide->order = $targetOrder;
        }

        $slide->save();
        return redirect()->route("admin.slides");
    }

    public function destroy($id)
    {
        $slide = Slide::find($id);

        $oldFileName = public_path('files/slides/' . $slide->file_name);
        if (file_exists($oldFileName)) {
            \File::delete($oldFileName);
        }
        
        $delOrder = $slide->order;
        $slide->delete();

        Slide::where('order', '>=', $delOrder)->decrement('order');

        return redirect()->route("admin.slides");
    }
}
