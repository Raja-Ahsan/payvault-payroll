@section('title', 'Packages')
@extends('layouts.admin.master')
@section('content')
<div class="container-fluid user-list-wrapper">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header card-no-border text-end">
                    <div class="card-header-right-icon">
                        <a class="btn btn-primary f-w-500" href="{{ route('packages.create') }}">
                            <i class="fa-solid fa-plus pe-2"></i>Add Package
                        </a>
                    </div>
                </div>
                <div class="card-body pt-0 px-0">
                    <div class="list-product user-list-table">
                        <div class="table-responsive custom-scrollbar">
                            <table class="table" id="packages-table">
                                <thead>
                                    <tr>
                                        <th><span class="c-o-light f-w-600">Title</span></th>
                                        <th><span class="c-o-light f-w-600">Price</span></th>
                                        <th><span class="c-o-light f-w-600">QuickBooks ID</span></th>
                                        <th><span class="c-o-light f-w-600">Home</span></th>
                                        <th><span class="c-o-light f-w-600">Actions</span></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($packages as $row)
                                        <tr>
                                            <td>{{ $row->title }}</td>
                                            <td>{{ $row->currency }} {{ number_format((float) $row->price, 2) }}</td>
                                            <td>{{ $row->quickbooks_item_id ?: '—' }}</td>
                                            <td>
                                                @if ($row->is_active)
                                                    <span class="badge badge-light-success">Visible</span>
                                                @else
                                                    <span class="badge badge-light-secondary">Hidden</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="common-align gap-2 justify-content-start">
                                                    <a class="square-white" href="{{ route('packages.edit', $row) }}">
                                                        <span><i class="fa-solid fa-pen"></i></span>
                                                    </a>
                                                    <form action="{{ route('packages.delete', $row) }}" method="POST">
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
                                        <tr>
                                            <td colspan="5" class="text-center text-muted py-4">No packages yet.</td>
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
@endsection

@push('scripts')
<script>
    ajaxDelete('.ajax-delete', 'tr');

    @if ($packages->isNotEmpty())
    var table = $('#packages-table').DataTable({
        order: [[0, 'asc']],
        columnDefs: [{
            orderable: false,
            targets: 4
        }]
    });
    @endif
</script>
@endpush
