<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\CompanyType;
use App\Models\State;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CompanyController extends Controller
{
    public function index()
    {
        $companies = Company::query()
            ->with(['federalTaxInformation.companyType'])
            ->get();

        return view('screens.admin.companies.index', get_defined_vars());
    }

    public function create()
    {
        $companyTypes = CompanyType::get();
        $states = State::query()->orderBy('name')->get();

        return view('screens.admin.companies.create', get_defined_vars());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            // step 1 general information
            'company_name' => 'required|string|max:255',
            'address_1' => 'required|string|max:255',
            'address_2' => 'nullable|string|max:255',
            'city' => 'required|string|max:255',
            'address_state_id' => ['required', 'integer', 'exists:states,id'],
            'zip_code' => 'required|string|max:255',
            'contact_name' => 'required|string|max:255',
            'tel_number' => 'required|string|max:255',
            'fax_number' => 'nullable|string|max:255',
            'email' => 'required|email|max:255',
            // step 2 federal tax information
            'company_type_id' => 'required|exists:company_types,id',
            'employer_identification_number' => 'required|string|max:255',
            'trade_name' => 'nullable|string|max:255',
            'round_federal_tax' => 'nullable|boolean',
            'control_number' => 'nullable|string|max:255',
            'establishment_number' => 'nullable|string|max:255',
            'other_ein' => 'nullable|string|max:255',
            // step 3 state tax information
            'state_id' => 'nullable|string|max:255',
            'first_fiscal_month' => 'required|string|max:255',
            'state_unemp_account_number' => 'nullable|string|max:255',
            'state_unemp_tax_rate_q1' => 'nullable|numeric|min:0',
            'state_unemp_tax_rate_q2' => 'nullable|numeric|min:0',
            'state_unemp_tax_rate_q3' => 'nullable|numeric|min:0',
            'state_unemp_tax_rate_q4' => 'nullable|numeric|min:0',
            'state_unemp_wage_base' => 'nullable|numeric|min:0',
            'first_fiscal_month' => 'required|string|max:255',
            'round_state_income_tax' => 'nullable|boolean',
            'hide_ssn_on_paystub' => 'nullable|boolean',
        ]);
        $userId = auth()->user()->id;
        $addressStateName = State::query()->findOrFail((int) $validated['address_state_id'])->name;

        DB::beginTransaction();

        try {
            $company = Company::create([
                'user_id' => $userId,
                'created_by' => $userId,
                // 'company_type_id' => $validated['company_type_id'],
                'company_name' => $validated['company_name'],
                'contact_name' => $validated['contact_name'],
                'tel_number' => $validated['tel_number'],
                'fax_number' => $validated['fax_number'],
                'email' => $validated['email'],
            ]);

            $company->address()->create([
                'address_1' => $validated['address_1'],
                'address_2' => $validated['address_2'],
                'city' => $validated['city'],
                'state' => $addressStateName,
                'zip_code' => $validated['zip_code'],
                'created_by' => $userId,
            ]);
            $company->federalTaxInformation()->create([
                'created_by' => $userId,
                'company_id' => $company->id,
                'company_type_id' => $validated['company_type_id'],
                'employer_identification_number' => $validated['employer_identification_number'],
                'trade_name' => $validated['trade_name'],
                'round_federal_tax' => $request->has('round_federal_tax'),
                'control_number' => $validated['control_number'],
                'establishment_number' => $validated['establishment_number'],
                'other_ein' => $validated['other_ein'],
            ]);

            $company->stateTaxInformation()->create([
                'created_by' => $userId,
                'company_id' => $company->id,
                'state_id' => $validated['state_id'],
                'first_fiscal_month' => $validated['first_fiscal_month'],
                'state_unemp_account_number' => $validated['state_unemp_account_number'],
                'state_unemp_tax_rate_q1' => $validated['state_unemp_tax_rate_q1'],
                'state_unemp_tax_rate_q2' => $validated['state_unemp_tax_rate_q2'],
                'state_unemp_tax_rate_q3' => $validated['state_unemp_tax_rate_q3'],
                'state_unemp_tax_rate_q4' => $validated['state_unemp_tax_rate_q4'],
                'state_unemp_wage_base' => $validated['state_unemp_wage_base'],
                'round_state_income_tax' => $request->has('round_state_income_tax'),
                'hide_ssn_on_paystub' => $request->has('hide_ssn_on_paystub'),
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Company created successfully',
            ]);
        } catch (\Exception $e) {

            DB::rollBack(); // ❌ ERROR → revert all

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function edit(Company $company)
    {
        $company->load(['address', 'federalTaxInformation.companyType', 'stateTaxInformation']);
        $companyTypes = CompanyType::query()->orderBy('title')->get();
        $states = State::query()->orderBy('name')->get();

        $addressStateId = old('address_state_id');
        if ($addressStateId === null && $company->address) {
            $addressStateId = State::query()
                ->where('name', $company->address->state)
                ->value('id')
                ?? State::query()
                    ->where('code', strtoupper((string) $company->address->state))
                    ->value('id');
        }

        return view('screens.admin.companies.edit', get_defined_vars());
    }

    public function update(Request $request, Company $company)
    {
        $validated = $request->validate([
            // step 1
            'company_name' => 'required|string|max:255',
            'address_1' => 'required|string|max:255',
            'address_2' => 'nullable|string|max:255',
            'city' => 'required|string|max:255',
            'address_state_id' => ['required', 'integer', 'exists:states,id'],
            'zip_code' => 'required|string|max:255',
            'contact_name' => 'required|string|max:255',
            'tel_number' => 'required|string|max:255',
            'fax_number' => 'nullable|string|max:255',
            'email' => 'required|email|max:255',

            // step 2
            'company_type_id' => 'required|exists:company_types,id',
            'employer_identification_number' => 'required|string|max:255',
            'trade_name' => 'nullable|string|max:255',
            'round_federal_tax' => 'nullable|boolean',
            'control_number' => 'nullable|string|max:255',
            'establishment_number' => 'nullable|string|max:255',
            'other_ein' => 'nullable|string|max:255',

            // step 3
            'state_id' => 'nullable|string|max:255',
            'first_fiscal_month' => 'required|string|max:255',
            'state_unemp_account_number' => 'nullable|string|max:255',
            'state_unemp_tax_rate_q1' => 'nullable|numeric|min:0',
            'state_unemp_tax_rate_q2' => 'nullable|numeric|min:0',
            'state_unemp_tax_rate_q3' => 'nullable|numeric|min:0',
            'state_unemp_tax_rate_q4' => 'nullable|numeric|min:0',
            'state_unemp_wage_base' => 'nullable|numeric|min:0',
            'round_state_income_tax' => 'nullable|boolean',
            'hide_ssn_on_paystub' => 'nullable|boolean',
        ]);

        $userId = auth()->id();
        $addressStateName = State::query()->findOrFail((int) $validated['address_state_id'])->name;

        DB::beginTransaction();

        try {

            // ✅ UPDATE COMPANY
            $company->update([
                'company_name' => $validated['company_name'],
                'contact_name' => $validated['contact_name'],
                'tel_number' => $validated['tel_number'],
                'fax_number' => $validated['fax_number'],
                'email' => $validated['email'],
            ]);

            // ✅ UPDATE ADDRESS
            if ($company->address) {
                $company->address->update([
                    'address_1' => $validated['address_1'],
                    'address_2' => $validated['address_2'],
                    'city' => $validated['city'],
                    'state' => $addressStateName,
                    'zip_code' => $validated['zip_code'],
                ]);
            } else {
                $company->address()->create([
                    'address_1' => $validated['address_1'],
                    'address_2' => $validated['address_2'],
                    'city' => $validated['city'],
                    'state' => $addressStateName,
                    'zip_code' => $validated['zip_code'],
                    'created_by' => $userId,
                ]);
            }

            $federalPayload = [
                'company_type_id' => $validated['company_type_id'],
                'employer_identification_number' => $validated['employer_identification_number'],
                'trade_name' => $validated['trade_name'],
                'round_federal_tax' => $request->has('round_federal_tax'),
                'control_number' => $validated['control_number'],
                'establishment_number' => $validated['establishment_number'],
                'other_ein' => $validated['other_ein'],
            ];

            if ($company->federalTaxInformation) {
                $company->federalTaxInformation->update($federalPayload);
            } else {
                $company->federalTaxInformation()->create(array_merge($federalPayload, [
                    'created_by' => $userId,
                    'company_id' => $company->id,
                ]));
            }

            $statePayload = [
                'state_id' => $validated['state_id'],
                'first_fiscal_month' => $validated['first_fiscal_month'],
                'state_unemp_account_number' => $validated['state_unemp_account_number'],
                'state_unemp_tax_rate_q1' => $validated['state_unemp_tax_rate_q1'],
                'state_unemp_tax_rate_q2' => $validated['state_unemp_tax_rate_q2'],
                'state_unemp_tax_rate_q3' => $validated['state_unemp_tax_rate_q3'],
                'state_unemp_tax_rate_q4' => $validated['state_unemp_tax_rate_q4'],
                'state_unemp_wage_base' => $validated['state_unemp_wage_base'],
                'round_state_income_tax' => $request->has('round_state_income_tax'),
                'hide_ssn_on_paystub' => $request->has('hide_ssn_on_paystub'),
            ];

            if ($company->stateTaxInformation) {
                $company->stateTaxInformation->update($statePayload);
            } else {
                $company->stateTaxInformation()->create(array_merge($statePayload, [
                    'created_by' => $userId,
                    'company_id' => $company->id,
                ]));
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Company updated successfully',
            ]);
        } catch (\Exception $e) {

            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function delete(Company $company)
    {
        $company->delete();

        return response()->json([
            'success' => true,
            'message' => 'Company deleted successfully',
        ]);
    }
}
