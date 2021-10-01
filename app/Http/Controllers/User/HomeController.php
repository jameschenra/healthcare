<?php

namespace App\Http\Controllers\User;

use App\Models\Slide;
use App\Models\MemberPlanType;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\HomeContent;
use App\Models\Testmonial;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function index() {
        $slides = Slide::orderBy('order')->get();
        $memberPlanTypes = MemberPlanType::all();
        $bodyContents = HomeContent::all();
        $testmonials = Testmonial::all();

        foreach ($bodyContents as $item) {
            $item->content = nl2br($item->content);
        }

        foreach ($testmonials as $item) {
            $item->content = nl2br($item->content);
        }

        return view('user.home', compact(
            'slides',
            'memberPlanTypes',
            'bodyContents',
            'testmonials'
        ));
    }

    public function priceTerms($type) {
        $plan = MemberPlanType::find($type);
        
        return view('user.priceterms', compact('plan'));
    }
}
