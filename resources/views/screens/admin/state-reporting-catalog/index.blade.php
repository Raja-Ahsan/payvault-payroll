@section('title', 'State reporting catalog')
@extends('layouts.admin.master')
@section('content')
<div class="container-fluid user-list-wrapper">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header card-no-border d-flex flex-wrap justify-content-between align-items-center gap-2">
                    <h4 class="mb-0 f-w-600">State reporting catalog</h4>
                    <div class="d-flex flex-wrap gap-2">
                        <a class="btn btn-sm button-light-primary" href="{{ route('admin.forms.index') }}">Back to Forms</a>
                        <a class="btn btn-sm btn-primary" href="{{ route('admin.forms.state-reporting.catalog.tax-types.create') }}">Add tax type</a>
                    </div>
                </div>
                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success py-2 small mb-3">{{ session('success') }}</div>
                    @endif
                    <p class="text-muted small mb-3">
                        Tax types and method options feed the State Tax Reporting wizard. Edit rows here—no Blade changes required for new states or copy.
                    </p>
                    @forelse ($grouped as $code => $rows)
                        <div class="mb-4">
                            <h6 class="f-w-600 border-bottom pb-2 mb-2">{{ $code }}</h6>
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>Label</th>
                                            <th>Slug</th>
                                            <th>Sort</th>
                                            <th>Active</th>
                                            <th>Methods</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($rows as $row)
                                            <tr>
                                                <td>{{ $row->label }}</td>
                                                <td><code>{{ $row->slug }}</code></td>
                                                <td>{{ $row->sort_order }}</td>
                                                <td>{{ $row->is_active ? 'Yes' : 'No' }}</td>
                                                <td>{{ $row->method_options_count }}</td>
                                                <td class="text-end text-nowrap">
                                                    <a class="btn btn-light btn-sm" href="{{ route('admin.forms.state-reporting.catalog.methods.index', $row) }}">Methods</a>
                                                    <a class="btn btn-light btn-sm" href="{{ route('admin.forms.state-reporting.catalog.tax-types.edit', $row) }}">Edit</a>
                                                    <form action="{{ route('admin.forms.state-reporting.catalog.tax-types.destroy', $row) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this tax type and all its methods?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-light btn-sm text-danger">Delete</button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @empty
                        <p class="text-muted mb-0">No tax types configured. Add one to enable state reporting options.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
