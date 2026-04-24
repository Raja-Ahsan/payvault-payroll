@section('title', $taxType ? 'Edit reporting tax type' : 'Add reporting tax type')
@extends('layouts.admin.master')
@section('content')
@php
    $isEdit = $taxType !== null;
@endphp
<div class="container-fluid user-list-wrapper">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-8">
            <div class="card">
                <div class="card-header card-no-border d-flex justify-content-between align-items-center">
                    <h4 class="mb-0 f-w-600">{{ $isEdit ? 'Edit' : 'Add' }} reporting tax type</h4>
                    <a class="btn btn-sm button-light-primary" href="{{ route('admin.forms.state-reporting.catalog.index') }}">Back</a>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ $isEdit ? route('admin.forms.state-reporting.catalog.tax-types.update', $taxType) : route('admin.forms.state-reporting.catalog.tax-types.store') }}">
                        @csrf
                        @if ($isEdit)
                            @method('PUT')
                        @endif
                        <div class="mb-3">
                            <label class="form-label" for="state_code">State code</label>
                            <select name="state_code" id="state_code" class="form-select form-select-sm @error('state_code') is-invalid @enderror" required>
                                @foreach ($states as $st)
                                    <option value="{{ $st->code }}" @selected(old('state_code', $taxType->state_code ?? '') === $st->code)>{{ $st->name }} ({{ $st->code }})</option>
                                @endforeach
                            </select>
                            @error('state_code')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="slug">Slug</label>
                            <input type="text" name="slug" id="slug" class="form-control form-control-sm @error('slug') is-invalid @enderror" value="{{ old('slug', $taxType->slug ?? '') }}" required pattern="[a-z0-9_]+" maxlength="64">
                            <div class="form-text">Lowercase letters, numbers, underscore only.</div>
                            @error('slug')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="label">Label</label>
                            <input type="text" name="label" id="label" class="form-control form-control-sm @error('label') is-invalid @enderror" value="{{ old('label', $taxType->label ?? '') }}" required maxlength="255">
                            @error('label')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="sort_order">Sort order</label>
                            <input type="number" name="sort_order" id="sort_order" class="form-control form-control-sm @error('sort_order') is-invalid @enderror" value="{{ old('sort_order', $taxType->sort_order ?? 0) }}" min="0">
                            @error('sort_order')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-check mb-3">
                            <input type="hidden" name="is_active" value="0">
                            <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1" @checked(old('is_active', $taxType?->is_active ?? true))>
                            <label class="form-check-label" for="is_active">Active</label>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="meta">Meta (JSON)</label>
                            <textarea name="meta" id="meta" class="form-control form-control-sm font-monospace @error('meta') is-invalid @enderror" rows="5" placeholder="{}">{{ old('meta', isset($taxType) && $taxType->meta ? json_encode($taxType->meta, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) : '') }}</textarea>
                            @error('meta')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <button type="submit" class="btn btn-primary btn-sm">{{ $isEdit ? 'Save' : 'Create' }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
