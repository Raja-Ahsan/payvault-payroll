@section('title', 'Create Company')
@extends('layouts.admin.master')
@section('content')
    <div class="container-fluid user-list-wrapper">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header card-no-border text-end">
                        {{-- <div class="card-header-right-icon">
                            <a class="btn btn-primary f-w-500" href="{{ route('users.create') }}">Open Company</a>
                            <a class="btn btn-primary f-w-500" href="{{ route('users.create') }}"><i
                                    class="fa-solid fa-plus pe-2"></i>Add
                                Company</a>
                        </div> --}}
                    </div>
                    <div class="card-body">
                    <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="pills-general-tab" data-bs-toggle="pill" data-bs-target="#pills-general" type="button" role="tab" aria-controls="pills-general" aria-selected="true">General Information</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="pills-federal-tab" data-bs-toggle="pill" data-bs-target="#pills-federal" type="button" role="tab" aria-controls="pills-federal" aria-selected="false">Federal Tax Information</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="pills-state-tab" data-bs-toggle="pill" data-bs-target="#pills-state" type="button" role="tab" aria-controls="pills-state" aria-selected="false">State Tax Informtaion</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="pills-income-tab" data-bs-toggle="pill" data-bs-target="#pills-income" type="button" role="tab" aria-controls="pills-income" aria-selected="false">Income Categories</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="pills-tax-categories-tab" data-bs-toggle="pill" data-bs-target="#pills-tax-categories" type="button" role="tab" aria-controls="pills-tax-categories" aria-selected="false">Income Categories</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="pills-deduction-categories-tab" data-bs-toggle="pill" data-bs-target="#pills-deduction-categories" type="button" role="tab" aria-controls="pills-deduction-categories" aria-selected="false">Deduction Categories</button>
                        </li>
                    </ul>
                        <div class="tab-content" id="pills-tabContent">
                        <div class="tab-pane fade show active" id="pills-general" role="tabpanel" aria-labelledby="pills-general-tab" tabindex="0">
                            <form class="ajax-form" action="{{ route('companies.general',) }}" id="submit-form" method="POST">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group mb-3">
                                            <label for="name">Company Name</label>
                                            <input type="text" class="form-control" id="company_name" name="company_name">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group mb-3">
                                            <label for="address_1">Address 1</label>
                                            <input type="text" class="form-control" id="address_1" name="address_1">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group mb-3">
                                            <label for="address_2">Address 2</label>
                                            <input type="text" class="form-control" id="address_2" name="address_2">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group mb-3">
                                            <label for="city">City</label>
                                            <input type="text" class="form-control" id="city" name="city">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group mb-3">
                                            <label for="state">State</label>
                                            <input type="text" class="form-control" id="state" name="state">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group mb-3">
                                            <label for="zip_code">Zip Code</label>
                                            <input type="text" class="form-control" id="zip_code" name="zip_code">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group mb-3">
                                            <label for="name">Contact Name</label>
                                            <input type="text" class="form-control" id="name" name="name">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group mb-3">
                                            <label for="phone">Telephone Number</label>
                                            <input type="text" class="form-control" id="phone" name="phone">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group mb-3">
                                            <label for="fax_number">Fax Number</label>
                                            <input type="text" class="form-control" id="fax_number" name="fax_number">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group mb-3">
                                            <label for="email">E-mail Address</label>
                                            <input type="text" class="form-control" id="email" name="email">
                                        </div>
                                    </div>
                                    <div class="col-md-12 d-flex justify-content-end">
                                        <div class="form-group mb-3">
                                            <button class="btn btn-primary" id="" type="submit">Add</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="tab-pane fade" id="pills-federal" role="tabpanel" aria-labelledby="pills-federal-tab" tabindex="0">...</div>
                        <div class="tab-pane fade" id="pills-state" role="tabpanel" aria-labelledby="pills-state-tab" tabindex="0">...</div>
                        <div class="tab-pane fade" id="pills-income" role="tabpanel" aria-labelledby="pills-income-tab" tabindex="0">...</div>
                        <div class="tab-pane fade" id="pills-tax-categories" role="tabpanel" aria-labelledby="pills-tax-categories-tab" tabindex="0">...</div>
                        <div class="tab-pane fade" id="pills-deduction-categories" role="tabpanel" aria-labelledby="pills-deduction-categories-tab" tabindex="0">...</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @push('scripts')
    <script>
        ajaxCreate("{{ route('companies.index') }}");
    </script>
    @endpush
@endsection
