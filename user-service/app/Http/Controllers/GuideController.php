<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class GuideController extends Controller
{
    /**
     * Display the guide page.
     */
    public function index(Request $request): View
    {
        return view('guide');
    }
}
