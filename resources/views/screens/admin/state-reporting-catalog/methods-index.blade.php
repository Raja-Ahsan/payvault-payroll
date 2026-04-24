@section('title', 'Reporting methods: '.$taxType->label)
@extends('layouts.admin.master')
@section('content')
<div class="container-fluid user-list-wrapper">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header card-no-border d-flex flex-wrap justify-content-between align-items-center gap-2">
                    <div>
                        <h4 class="mb-0 f-w-600">Method options</h4>
                        <p class="text-muted small mb-0">{{ $taxType->state_code }} — {{ $taxType->label }} <code>{{ $taxType->slug }}</code></p>
                    </div>
                    <div class="d-flex flex-wrap gap-2">
                        <a class="btn btn-sm button-light-primary" href="{{ route('admin.forms.state-reporting.catalog.index') }}">Catalog</a>
                        <a class="btn btn-sm btn-primary" href="{{ route('admin.forms.state-reporting.catalog.methods.create', $taxType) }}">Add method</a>
                    </div>
                </div>
                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success py-2 small mb-3">{{ session('success') }}</div>
                    @endif
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Label</th>
                                    <th>Slug</th>
                                    <th>Flow</th>
                                    <th>Sort</th>
                                    <th>Active</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($methods as $m)
                                    <tr>
                                        <td>{{ $m->label }}</td>
                                        <td><code>{{ $m->slug }}</code></td>
                                        <td>{{ $flowKinds[$m->flow_kind] ?? $m->flow_kind }}</td>
                                        <td>{{ $m->sort_order }}</td>
                                        <td>{{ $m->is_active ? 'Yes' : 'No' }}</td>
                                        <td class="text-end text-nowrap">
                                            <a class="btn btn-light btn-sm" href="{{ route('admin.forms.state-reporting.catalog.methods.edit', [$taxType, $m]) }}">Edit</a>
                                            <form action="{{ route('admin.forms.state-reporting.catalog.methods.destroy', [$taxType, $m]) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this method?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-light btn-sm text-danger">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-muted py-3">No methods yet. Add at least one for the wizard step after “Reported tax”.</td>
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
@endsection
