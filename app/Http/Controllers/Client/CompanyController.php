<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CompanyController extends Controller
{
    public function index()
    {
        $companies = Company::where('created_by', Auth::id())
            ->latest()
            ->paginate(15);
        return view('client.companies.index', compact('companies'));
    }

    public function create()
    {
        return view('client.companies.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'legal_name' => 'nullable|string|max:255',
            'ein' => 'nullable|string|unique:companies,ein',
            'address' => 'nullable|string',
            'city' => 'nullable|string',
            'state' => 'nullable|string',
            'zip_code' => 'nullable|string',
            'phone' => 'nullable|string',
            'email' => 'nullable|email',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $company = Company::create([
            ...$request->all(),
            'created_by' => Auth::id(),
        ]);

        return redirect()->route('client.companies.index')
            ->with('success', 'Company created successfully!');
    }

    public function show(Company $company)
    {
        // Ensure user owns this company
        if ($company->created_by !== Auth::id()) {
            abort(403, 'Unauthorized access.');
        }

        $company->load(['employees', 'payrollRuns']);
        return view('client.companies.show', compact('company'));
    }

    public function edit(Company $company)
    {
        // Ensure user owns this company
        if ($company->created_by !== Auth::id()) {
            abort(403, 'Unauthorized access.');
        }

        return view('client.companies.edit', compact('company'));
    }

    public function update(Request $request, Company $company)
    {
        // Ensure user owns this company
        if ($company->created_by !== Auth::id()) {
            abort(403, 'Unauthorized access.');
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'legal_name' => 'nullable|string|max:255',
            'ein' => 'nullable|string|unique:companies,ein,' . $company->id,
            'address' => 'nullable|string',
            'city' => 'nullable|string',
            'state' => 'nullable|string',
            'zip_code' => 'nullable|string',
            'phone' => 'nullable|string',
            'email' => 'nullable|email',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $company->update($request->all());

        return redirect()->route('client.companies.index')
            ->with('success', 'Company updated successfully!');
    }

    public function destroy(Company $company)
    {
        // Ensure user owns this company
        if ($company->created_by !== Auth::id()) {
            abort(403, 'Unauthorized access.');
        }

        $company->delete();
        return redirect()->route('client.companies.index')
            ->with('success', 'Company deleted successfully!');
    }
}
