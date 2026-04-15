<?php

namespace App\Http\Controllers;

use App\Models\Package;

class HomeController extends Controller
{
    public function index()
    {
        $packages = Package::activeForHome()->get();

        return view('screens.web.home.index', compact('packages'));
    }
}
