<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Enums\ContentType;
use App\Models\HomeContent;
use App\Models\ContactInfo;
use App\Models\WorkingHour;
use Illuminate\Support\Facades\DB;

class ContentController extends Controller
{
    public function bodyContent(Request $request)
    {
        $segments = $request->segments();
        $id = end($segments);

        $content = HomeContent::find($id);

        return view('admin.contents.body_edit', compact(
            'content'
        ));
    }

    public function updateBodyContent(Request $request) {

        $content = HomeContent::find($request->input('id'));
        $content->content = $request->input("content");

        $imageFile = $request->img_file;
        if ($imageFile) {
            $oldFileName = public_path('files/contents/' . $content->image);
            if (file_exists($oldFileName)) {
                \File::delete($oldFileName);
            }

            $fileName = time() . '.' . $imageFile->getClientOriginalExtension();
            $imageFile->move(public_path('files/contents'), $fileName);
            $content->image = $fileName;
        }

        $content->save();
        return redirect()->back();
    }

    public function contactContent() {
        $content = ContactInfo::first();

        return view('admin.contents.contact_edit', compact(
            'content'
        ));
    }

    public function updateContact(Request $request) {
        $this->validate($request, [
            'email' => 'required|email',
            'phone' => 'required',
            'address' => 'required',
            'facebook' => 'required',
            'twitter' => 'required',
            'instagram' => 'required',
            'pinterest' => 'required',
        ]);

        ContactInfo::first()->update($request->all());

        return redirect()->back();
    }

    public function workingHours() {
        $workingHours = DB::table('working_hours')->get();

        return view('admin.contents.working_edit', compact(
            'workingHours'
        ));
    }

    public function updateWorkingHours(Request $request) {
        $hourList = [];

        // dd($request->input('open_status'));
        for ($i=0; $i<7; $i++) {
            
            $status = 0;
            if (array_search(($i), $request->input('open_status')) !== false) {
                $status = 1;
            }
            
            WorkingHour::find($i+1)->update([
                'start' => $request->input('start')[$i],
                'end' => $request->input('end')[$i],
                'open_status' => $status,
            ]);
        }

        return redirect()->back();
    }
}
