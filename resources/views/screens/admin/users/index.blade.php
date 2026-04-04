@section('title', 'All Users')
@extends('layouts.admin.master')
@section('content')
    <div class="container-fluid user-list-wrapper">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header card-no-border text-end">
                        <div class="card-header-right-icon">
                            <a class="btn btn-primary f-w-500" href="{{ route('users.create') }}"><i
                                    class="fa-solid fa-plus pe-2"></i>Add
                                User</a>
                        </div>
                    </div>
                    <div class="card-body pt-0 px-0">
                        <div class="list-product user-list-table">
                            <div class="table-responsive custom-scrollbar">
                                <table class="table" id="users-table">
                                    <thead>
                                        <tr>
                                            <th>
                                                <span class="c-o-light f-w-600">Name</span>
                                            </th>
                                            <th>
                                                <span class="c-o-light f-w-600">Email</span>
                                            </th>
                                            {{-- <th>
                                                <span class="c-o-light f-w-600">Role</span>
                                            </th> --}}
                                            <th>
                                                <span class="c-o-light f-w-600">Creation Date</span>
                                            </th>
                                            <th>
                                                <span class="c-o-light f-w-600">Actions</span>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($users as $user)
                                            <tr class="product-removes inbox-data">
                                                <td><a href="user-profile.html">{{ $user->name }}</a></td>
                                                <td>
                                                    <p>{{ $user->email }}</p>
                                                </td>
                                                {{-- <td>
                                                    <span
                                                        class="badge badge-light-success">{{ $user->roles->first()->name ?? 'No Role' }}</span>
                                                </td> --}}
                                                <td>
                                                    <p>{{ $user->created_at->format('d M Y, H:i A') }}</p>
                                                </td>
                                                <td>
                                                    <div class="common-align gap-2 justify-content-start">
                                                        {{-- <a class="square-white" href="add-user.html">
                                                            <span><i class="fa-solid fa-pen"></i></span>
                                                        </a>
                                                        <a class="square-white trash-7" href="#!">
                                                            <span><i class="fa-solid fa-trash"></i></span>
                                                        </a> --}}
                                                        {{-- add view icon --}}
                                                        <a class="square-white" href="#!">
                                                            <span><i class="fa-solid fa-eye"></i></span>
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="text-center">
                                                    <h3 class="pt-5">No @yield('title', 'Dashboard') Found</h3>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- @push('scripts')
    <script>
        ajaxCreate('#createUserForm', "{{ route('users.index') }}");
    </script>
    @endpush --}}
@endsection
