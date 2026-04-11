@section('title', 'Deduction Categories')
@extends('layouts.admin.master')
@section('content')
<div class="container-fluid user-list-wrapper">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header card-no-border text-end">
                    <div class="card-header-right-icon">
                        <a class="btn btn-primary f-w-500" href="{{ route('categories.deduction.create') }}">
                            <i class="fa-solid fa-plus pe-2"></i>Add Deduction Category
                        </a>
                    </div>
                </div>
                <div class="card-body pt-0 px-0">
                    <div class="list-product user-list-table">
                        <div class="table-responsive custom-scrollbar">
                            <table class="table" id="deduction-categories-table">
                                <thead>
                                    <tr>
                                        <th><span class="c-o-light f-w-600">Title</span></th>
                                        <th><span class="c-o-light f-w-600">Calculation</span></th>
                                        <th><span class="c-o-light f-w-600">Actions</span></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($deductionCategories as $row)
                                        <tr>
                                            <td>{{ $row->title }}</td>
                                            <td>
                                                <!-- {{ $calculationOptions[$row->calculation] ?? (filled($row->calculation) ? ucwords(str_replace('_', ' ', (string) $row->calculation)) : '—') }} -->
                                                {{ $row->incomeType->title }}
                                            </td>
                                            <td>
                                                <div class="common-align gap-2 justify-content-start">
                                                    <a class="square-white" href="{{ route('categories.deduction.edit', $row) }}">
                                                        <span><i class="fa-solid fa-pen"></i></span>
                                                    </a>
                                                    <form action="{{ route('categories.deduction.delete', $row) }}" method="POST">
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
                                            <td colspan="3" class="text-center text-muted py-4">No deduction categories yet.</td>
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

    $('#deduction-categories-table').DataTable({
        order: [[0, 'asc']],
        columnDefs: [{
            orderable: false,
            targets: 2
        }]
    });
</script>
@endpush
