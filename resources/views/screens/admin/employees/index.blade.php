
@section('title', 'Company')
@extends('layouts.admin.master')
@section('content')
    <div class="container-fluid user-list-wrapper">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header card-no-border text-end">
                        <div class="card-header-right-icon">
                            <a class="btn btn-primary f-w-500" href="{{ route('employees.create') }}"><i
                                    class="fa-solid fa-plus pe-2"></i>Add
                                Employee</a>
                        </div>
                    </div>
                    <div class="card-body pt-0 px-0">
                        <div class="list-product user-list-table">
                            <div class="table-responsive custom-scrollbar">
                                <table class="table" id="users-table">
                                    <thead>
                                        <tr>
                                            <th>
                                                <span class="c-o-light f-w-600">First Name</span>
                                            </th>
                                            <th>
                                                <span class="c-o-light f-w-600">Middle Name</span>
                                            </th>
                                            <th>
                                                <span class="c-o-light f-w-600">Last Name</span>
                                            </th>
                                            <th>
                                                <span class="c-o-light f-w-600">SSN Number</span>
                                            </th>
                                            <th>
                                                <span class="c-o-light f-w-600">Phone Number</span>
                                            </th>
                                            <th>
                                                <span class="c-o-light f-w-600">Address 1</span>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        {{-- @forelse ($companies as $company )
                                             <tr>
                                                 <td>
                                                     {{$company->company_name ?? '-'}}
                                                 </td>
                                                 <td>
                                                     {{ $company->federalTaxInformation?->companyType?->title ?? '-' }}
                                                 </td>
                                                 <td>
                                                     {{$company->created_at->format('d-m-Y') ?? '-'}}
                                                 </td>
                                                 <td>
                                                    <div class="common-align gap-2 justify-content-start">
                                                        <a class="square-white" href="">
                                                            <span><i class="fa-solid fa-eye"></i></span>
                                                        </a>
                                                        <a class="square-white" href="{{route('companies.edit', $company->id)}}">
                                                            <span><i class="fa-solid fa-pen"></i></span>
                                                        </a>
                                                        <form
                                                            action="{{ route('companies.delete', $company->id) }}"
                                                            method="POST">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="square-white ajax-delete">
                                                                <span><i class="fa-solid fa-trash"></i></span>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                             </tr>
                                        @empty

                                        @endforelse --}}
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        ajaxDelete('.ajax-delete', 'tr');
    </script>
@endpush