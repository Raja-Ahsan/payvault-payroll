@section('title', 'Create User')
@extends('layouts.admin.master')
@section('content')
<div class="container-fluid">
    <div class="edit-profile">
        <form class="card" id="createUserForm" action={{ route('users.store') }} method="POST">
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
                    <div class="col-sm-6 col-md-6">
                        <div class="mb-3">
                            <label class="form-label" for="name">Name <span class="text-danger">*</span></label>
                            <input class="form-control @error('name') is-invalid @enderror" id="name"
                                type="text" placeholder="Enter Name" name="name" />
                            @error('name')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-6">
                        <div class="mb-3">
                            <label class="form-label" for="email">Email Address <span
                                    class="text-danger">*</span></label><input class="form-control" id="email"
                                type="email" placeholder="Enter Email Address" name="email" />
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-6">
                        <div class="mb-3">
                            {{-- <label class="form-label" for="role">Role <span class="text-danger">*</span></label> --}}
                            {{-- @role('super_admin')
                            <select class="form-control" id="role" name="role_id" placeholder="Select Role">
                                <option value="" selected>-- Select Role --</option>
                                @foreach ($roles as $id => $name)
                                    <option value="{{ $id }}">{{ $name }}</option>
                                @endforeach
                            </select>
                            @else 
                            <input type="hidden" name="role_id" value="{{ auth()->user()->roles->first()->id }}">
                            <div class="form-control">
                                {{ auth()->user()->roles->first()->name ?? 'Your Role' }}
                            </div>
                            @endrole --}}
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-6">
                        <div class="mb-3">
                            {{-- <select class="form-control" id="company" name="company_id" placeholder="Select Company">
                                <option value="" selected>-- Select Company --</option>
                                @foreach ($companies as $id => $name) 
                                <option value="{{ $id }}">{{ $name }}</option>
                                @endforeach
                            </select> --}}
                            
                            {{-- <label class="form-label" for="company">Company <span class="text-danger">*</span></label>
                            @role('super_admin')
                                <select class="form-control" id="company" name="company_id" required>
                                    <option value="">Select Company</option>
                                    @foreach ($companies as $id => $name)
                                        <option value="{{ $id }}">{{ $name }}</option>
                                    @endforeach
                                </select>
                            @else
                                <input type="hidden" name="company_id" value="{{ auth()->user()->company_id }}">
                                <div class="form-control">
                                    {{ auth()->user()->company->name ?? 'Your Company' }}
                                </div>
                            @endrole --}}
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-6">
                        <div class="mb-3">
                            <label class="form-label" for="password">Password <span
                                    class="text-danger">*</span></label><input class="form-control" id="password"
                                type="password" placeholder="Enter Password" name="password" />
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-6">
                        <div class="mb-3">
                            <label class="form-label" for="confirm_password">Confirm Password</label><input
                                class="form-control" id="confirm_password" type="password"
                                placeholder="Enter Confirm Password" name="password_confirmation" />
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