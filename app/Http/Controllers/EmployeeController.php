<?php

namespace App\Http\Controllers;

use App\Models\State;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EmployeeController extends Controller
{
    public function index()
    {
        return view('screens.admin.employees.index', get_defined_vars()); 
    }

    public function create()
    {
        $states = State::all();
        return view('screens.admin.employees.create', get_defined_vars());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'address_1' => 'required|string|max:255',
            'address_2' => 'nullable|string|max:255',
            'city' => 'required|string|max:255',
            'state_id' => 'required|exists:states,id',
            'zip_code' => 'required|string|max:255',
            'ssn' => 'required|string|max:255',
            'dob' => 'nullable|date',
            'phone' => 'nullable|string|max:255',
            'fax' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'employee_id' => 'nullable|string|max:255',
            'message' => 'nullable|string|max:255',
            'inactive' => 'nullable|boolean',
        ]);

        dd($validated);

        // DB::beginTransaction();

        // try {
        //     $employee = Employee::c
        // }
    }
}
