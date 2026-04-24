@section('title', $method ? 'Edit method option' : 'Add method option')
@extends('layouts.admin.master')
@section('content')
@php
    $isEdit = $method !== null;
@endphp
<div class="container-fluid user-list-wrapper">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-8">
            <div class="card">
                <div class="card-header card-no-border d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-0 f-w-600">{{ $isEdit ? 'Edit' : 'Add' }} method</h4>
                        <p class="text-muted small mb-0">{{ $taxType->state_code }} — {{ $taxType->label }}</p>
                    </div>
                    <a class="btn btn-sm button-light-primary" href="{{ route('admin.forms.state-reporting.catalog.methods.index', $taxType) }}">Back</a>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ $isEdit ? route('admin.forms.state-reporting.catalog.methods.update', [$taxType, $method]) : route('admin.forms.state-reporting.catalog.methods.store', $taxType) }}">
                        @csrf
                        @if ($isEdit)
                            @method('PUT')
                        @endif
                        <div class="mb-3">
                            <label class="form-label" for="slug">Slug</label>
                            <input type="text" name="slug" id="slug" class="form-control form-control-sm @error('slug') is-invalid @enderror" value="{{ old('slug', $method->slug ?? '') }}" required pattern="[a-z0-9_]+" maxlength="64">
                            @error('slug')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="label">Label</label>
                            <input type="text" name="label" id="label" class="form-control form-control-sm @error('label') is-invalid @enderror" value="{{ old('label', $method->label ?? '') }}" required maxlength="255">
                            @error('label')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="flow_kind">Flow kind</label>
                            <select name="flow_kind" id="flow_kind" class="form-select form-select-sm @error('flow_kind') is-invalid @enderror" required>
                                @foreach ($flowKinds as $val => $lbl)
                                    <option value="{{ $val }}" @selected(old('flow_kind', $method->flow_kind ?? '') === $val)>{{ $lbl }}</option>
                                @endforeach
                            </select>
                            <div class="form-text"><strong>ICESA</strong> adds the transmitter / .ICE path step in the state reporting wizard.</div>
                            @error('flow_kind')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="link_text">Link text (optional)</label>
                            <input type="text" name="link_text" id="link_text" class="form-control form-control-sm @error('link_text') is-invalid @enderror" value="{{ old('link_text', $method->link_text ?? '') }}" maxlength="255">
                            @error('link_text')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="description">Description</label>
                            <textarea name="description" id="description" class="form-control form-control-sm @error('description') is-invalid @enderror" rows="4">{{ old('description', $method->description ?? '') }}</textarea>
                            @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="sort_order">Sort order</label>
                            <input type="number" name="sort_order" id="sort_order" class="form-control form-control-sm @error('sort_order') is-invalid @enderror" value="{{ old('sort_order', $method->sort_order ?? 0) }}" min="0">
                            @error('sort_order')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-check mb-3">
                            <input type="hidden" name="is_active" value="0">
                            <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1" @checked(old('is_active', $method?->is_active ?? true))>
                            <label class="form-check-label" for="is_active">Active</label>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="meta">Meta (JSON)</label>
                            <textarea name="meta" id="meta" class="form-control form-control-sm font-monospace @error('meta') is-invalid @enderror" rows="6" placeholder='{"icesa_intro":"..."}'>{{ old('meta', isset($method) && $method->meta ? json_encode($method->meta, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) : '') }}</textarea>
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
