<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class CheckController extends Controller
{
    public function index(): View
    {
        return view('screens.admin.checks.index');
    }

    public function create(): View
    {
        return view('screens.admin.checks.create');
    }

    public function show(int $check): View
    {
        return view('screens.admin.checks.show', ['checkId' => $check]);
    }

    public function edit(int $check): View
    {
        return view('screens.admin.checks.edit', ['checkId' => $check]);
    }
}
