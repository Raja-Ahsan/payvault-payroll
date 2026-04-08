@section('title', 'Company')
@extends('layouts.admin.master')
@section('content')
<div class="container-fluid user-list-wrapper">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header card-no-border text-end">
                    <div class="card-header-right-icon">
                        <a class="btn btn-primary f-w-500" href="{{route('categories.income.create')}}"><i
                                class="fa-solid fa-plus pe-2"></i>Add New
                            Category</a>
                    </div>
                </div>
                <div class="card-body pt-0 px-0">
                    <div class="list-product user-list-table">
                        <div class="table-responsive custom-scrollbar">
                            <table class="table" id="income-categories-table">
                                <thead>
                                    <tr>
                                        <th>
                                            <span class="c-o-light f-w-600">Title</span>
                                        </th>
                                        <th>
                                            <span class="c-o-light f-w-600">Calculation</span>
                                        </th>
                                        <!-- <th>
                                            <span class="c-o-light f-w-600">Actions</span>
                                        </th> -->
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($categories as $category )
                                    <tr>
                                        <td>
                                            {{$category->title ?? '-'}}
                                        </td>
                                        <td>
                                            {{$category->incomeType->title ?? '-'}}
                                        </td>
                                        
                                    </tr>
                                    @empty

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

    var table = $('#income-categories-table').DataTable({
        order: [
            [3, 'desc']
        ],
        columnDefs: [{
            orderable: false,
            targets: 1
        }]
    });
</script>
@endpush