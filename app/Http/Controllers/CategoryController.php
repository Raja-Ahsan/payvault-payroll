<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        return view('screens.admin.categories.index');
    }

    public function create()
    {
        return view('screens.admin.categories.create');
    }
}
