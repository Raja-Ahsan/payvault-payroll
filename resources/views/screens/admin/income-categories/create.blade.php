@section('title', 'Create User')
@extends('layouts.admin.master')
@section('content')
<div class="container-fluid">
    <div class="edit-profile">
        <form class="card" id="createIncomeCategoryForm" action="{{route('categories.income.store')}}" method="POST">
            @csrf
            <div class="card-header">
                <div class="card-options">
                    <a class="card-options-collapse" href="#" data-bs-toggle="card-collapse"><i
                            class="fe fe-chevron-up"></i></a><a class="card-options-remove" href="#"
                        data-bs-toggle="card-remove"><i class="fe fe-x"></i></a>
                </div>
            </div>
            <div class="card-body">
                <div class="row custom-input">
                    <div class="col-sm-6 col-md-4">
                        <div class="form-group mb-3">
                            <label class="form-label" for="name">Title</label>
                            <input class="form-control" id="title"
                                type="text" placeholder="Enter Title" name="title" />
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-4">
                        <div class="form-group mb-3">
                            <label class="form-label" for="abbreviation">Abbreviation</label>
                            <input class="form-control" id="abbreviation"
                                type="text" placeholder="Enter Abbreviation" name="abbreviation" />
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-4">
                        <div class="form-group mb-3">
                            <label for="name">Type</label>
                            <select class="form-select" id="income_type_id" name="income_type_id" required>
                                @forelse ($incomeTypes ?? [] as $type)
                                <option value="{{ $type->id }}">{{ $type->title }}</option>
                                @empty
                                <option value="" disabled selected>No income types available</option>
                                @endforelse
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-4">
                        <div class="form-group mb-3">
                            <label class="form-label" for="w2_box_12">W-2 Box 12 Code</label>
                            <input class="form-control" id="w2_box_12"
                                type="text" placeholder="Enter W-2 Box 12 Code" name="w2_box_12" />
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-4">
                        <div class="form-group mb-3">
                            <label class="form-label" for="w2_box_14">W-2 Box 14 Abbreviation</label>
                            <input class="form-control" id="w2_box_14"
                                type="text" placeholder="Enter W-2 Box 14 Abbreviation" name="w2_box_14" />
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-group mb-3">
                            <input
                                name="round_federal_tax"
                                class="form-check-input mt-0"
                                id="round_federal_tax"
                                type="checkbox"
                                value=""
                                checked="" />
                            <label
                                class="form-check-label mb-0"
                                for="round_federal_tax">Round Federal Income Tax to the Nearest Dollar</label>
                        </div>
                        <div class="form-group mb-3">
                            <input
                                name="omit_from_net_pay"
                                class="form-check-input mt-0"
                                id="omit_from_net_pay"
                                type="checkbox"
                                value=""
                                checked="" />
                            <label
                                class="form-check-label mb-0"
                                for="omit_from_net_pay">Omit from Net Pay</label>
                        </div>
                        <div class="form-group mb-3">
                            <input
                                name="inactive"
                                class="form-check-input mt-0"
                                id="inactive"
                                type="checkbox"
                                value=""
                                checked="" />
                            <label
                                class="form-check-label mb-0"
                                for="inactive">Inactive</label>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="row">
                            <div class="col-lg-6">
                                <h2 class="mb-4">Taxes Applied</h2>
                                <div class="form-group mb-3">
                                    <input class="form-check-input mt-0" type="checkbox" value="1" id="federal_income_tax" name="federal_income_tax" />
                                    <label class="form-check-label mb-0" for="federal_income_tax">Federal Income Tax</label>
                                </div>
                                <div class="form-group mb-3">
                                    <input class="form-check-input mt-0" type="checkbox" value="1" id="social_security_employee" name="social_security_employee" />
                                    <label class="form-check-label mb-0" for="social_security_employee">Social Security (Employee)</label>
                                </div>
                                <div class="form-group mb-3">
                                    <input class="form-check-input mt-0" type="checkbox" value="1" id="social_security_employer" name="social_security_employer" />
                                    <label class="form-check-label mb-0" for="social_security_employer">Social Security (Employer)</label>
                                </div>
                                <div class="form-group mb-3">
                                    <input class="form-check-input mt-0" type="checkbox" value="1" id="medicare_employee" name="medicare_employee" />
                                    <label class="form-check-label mb-0" for="medicare_employee">Medicare (Employee)</label>
                                </div>
                                <div class="form-group mb-3">
                                    <input class="form-check-input mt-0" type="checkbox" value="1" id="medicare_employer" name="medicare_employer" />
                                    <label class="form-check-label mb-0" for="medicare_employer">Medicare (Employer)</label>
                                </div>
                                <div class="form-group mb-3">
                                    <input class="form-check-input mt-0" type="checkbox" value="1" id="fed_unemployment_employee" name="fed_unemployment_employee" />
                                    <label class="form-check-label mb-0" for="fed_unemployment_employee">Fed Unemployment (Employee)</label>
                                </div>
                                <div class="form-group mb-3">
                                    <input class="form-check-input mt-0" type="checkbox" value="1" id="state_income_tax" name="state_income_tax" />
                                    <label class="form-check-label mb-0" for="state_income_tax">State Income Tax</label>
                                </div>
                                <div class="form-group mb-3">
                                    <input class="form-check-input mt-0" type="checkbox" value="1" id="state_unemployment_employee" name="state_unemployment_employee" />
                                    <label class="form-check-label mb-0" for="state_unemployment_employee">State Unemployment (Employee)</label>
                                </div>
                                <div class="form-group mb-3">
                                    <input class="form-check-input mt-0" type="checkbox" value="1" id="local_income_tax" name="local_income_tax" />
                                    <label class="form-check-label mb-0" for="local_income_tax">Local Income Tax</label>
                                </div>
                                <div class="form-group mb-3">
                                    <input class="form-check-input mt-0" type="checkbox" value="1" id="state_disability_insurance_employee" name="state_disability_insurance_employee" />
                                    <label class="form-check-label mb-0" for="state_disability_insurance_employee">State Disability Insurance (Employee)</label>
                                </div>
                                <div class="form-group mb-3">
                                    <input class="form-check-input mt-0" type="checkbox" value="1" id="state_disability_insurance_employer" name="state_disability_insurance_employer" />
                                    <label class="form-check-label mb-0" for="state_disability_insurance_employer">State Disability Insurance (Employer)</label>
                                </div>
                                <div class="form-group mb-3">
                                    <input class="form-check-input mt-0" type="checkbox" value="1" id="new_york_city_tax" name="new_york_city_tax" />
                                    <label class="form-check-label mb-0" for="new_york_city_tax">New York City Tax</label>
                                </div>
                                <div class="form-group mb-3">
                                    <input class="form-check-input mt-0" type="checkbox" value="1" id="etc" name="etc" />
                                    <label class="form-check-label mb-0" for="etc">etc</label>
                                </div>
                                
                            </div>
                            <div class="col-lg-6">
                                <h2 class="mb-4">Deductions Applied</h2>
                                <div class="form-group mb-3">
                                    <input class="form-check-input mt-0" type="checkbox" value="1" id="401k_employee" name="401k_employee" />
                                    <label class="form-check-label mb-0" for="401k_employee">401K (Employee)</label>
                                </div>
                                <div class="form-group mb-3">
                                    <input class="form-check-input mt-0" type="checkbox" value="1" id="401k_employer" name="401k_employer" />
                                    <label class="form-check-label mb-0" for="401k_employer">401K (Employer)</label>
                                </div>
                                <div class="form-group mb-3">
                                    <input class="form-check-input mt-0" type="checkbox" value="1" id="health_insurance" name="health_insurance" />
                                    <label class="form-check-label mb-0" for="health_insurance">Health Insurance</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer text-end">
                <button class="btn btn-primary" type="submit">
                    Create
                </button>
            </div>
        </form>
    </div>
</div>
@push('scripts')
<script>
    ajaxCreate('#createUserForm', "{{ route('users.index') }}");
</script>
@endpush
@endsection