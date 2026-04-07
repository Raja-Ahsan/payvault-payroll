<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Address;
use App\Models\UserInformation;
use App\Models\User;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    public function index()
    {
        return view('screens.admin.companies.index');
    }
    public function general(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address_1' => 'required|string|max:255',
            'address_2' => 'nullable|string|max:255',
            'city' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'zip_code' => 'required|string|max:255',
            'phone' => 'required|string|max:255',
            'fax_number' => 'nullable|string|max:255',
            'email' => 'required|email|max:255',
        ]);
        $userId = auth()->user()->id;
        $company = Company::create([
            'user_id' => $userId,
            'created_by' => $userId,
            'company_name' => $validated['name'],
        ]);

        User::where('id', $userId)->update([
            'phone' => $validated['phone'],
            'fax_number' => $validated['fax_number'],
        ]);

        $company->address()->create([
            'address_1' => $validated['address_1'],
            'address_2' => $validated['address_2'],
            'city' => $validated['city'],
            'state' => $validated['state'],
            'zip_code' => $validated['zip_code'],
            'created_by' => $userId,
        ]);
       
        $company_id = $company->id;
        
        return response()->json(['message' => 'Company created successfully']);
    }
}
