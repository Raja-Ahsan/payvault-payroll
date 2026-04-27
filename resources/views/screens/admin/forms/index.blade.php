@section('title', 'Forms')
@extends('layouts.admin.master')
@section('content')
@php
    $formTiles = [
        ['code' => 'W-2', 'label' => 'Wage and Tax Statement', 'href' => route('admin.forms.w2')],
        ['code' => '941', 'label' => 'Employer\'s Quarterly Federal Tax Return', 'href' => route('admin.forms.form-941')],
        ['code' => '941-X', 'label' => 'Adjusted Employer\'s Quarterly Federal Tax Return or Claim for Refund', 'href' => route('admin.forms.form-941-x')],
        ['code' => '940', 'label' => 'Employer\'s Annual Federal Unemployment (FUTA)', 'href' => route('admin.forms.940')],
        ['code' => '944', 'label' => 'Employer\'s ANNUAL Federal Tax Return', 'href' => route('admin.forms.944')],
        ['code' => 'STATE', 'label' => 'State Reporting', 'href' => route('admin.forms.state-reporting')],
    ];
@endphp
<div class="container-fluid user-list-wrapper">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card">
                <!-- <div class="card-header card-no-border pb-0">
                    <h4 class="mb-0 f-w-600">Forms</h4>
                    <p class="text-muted small mb-0 mt-2">Federal and state payroll tax forms. Select a form to open its workspace (coming soon).</p>
                </div> -->
                <div class="card-body pt-4">
                    @if (auth()->user()?->hasRole('admin'))
                        <div class="text-end mb-3">
                            <a href="{{ route('admin.forms.state-reporting.catalog.index') }}" class="btn btn-sm btn-outline-secondary">State reporting catalog</a>
                        </div>
                    @endif
                    <div class="row g-4">
                        @foreach ($formTiles as $tile)
                            <div class="col-md-6">
                                <a href="{{ $tile['href'] }}" class="forms-grid-tile d-block h-100 text-decoration-none text-reset">
                                    <div class="card h-100 border shadow-none mb-0">
                                        <div class="card-body text-center p-4 d-flex flex-column align-items-center">
                                            <div class="rounded-3 bg-primary text-white w-100 py-4 px-3 mb-3 d-flex align-items-center justify-content-center fw-bold forms-grid-code">
                                                {{ $tile['code'] }}
                                            </div>
                                            <span class="text-primary text-decoration-underline small lh-sm">{{ $tile['label'] }}</span>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    </div>
                    <div class="text-center text-muted small mt-5 pt-2 border-top">
                        <p class="mb-2 mb-md-1">Forms are periodically updated throughout the year.</p>
                        <p class="mb-2 mb-md-1">If the form you are looking at is not updated, it will be updated before its due date.</p>
                        <p class="mb-0">Make sure you always keep {{ config('app.name') }} up to date.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .forms-grid-code {
        font-size: clamp(1.5rem, 4vw, 2rem);
        letter-spacing: 0.02em;
        min-height: 5.5rem;
    }
    .forms-grid-tile .card {
        transition: border-color 0.2s ease, box-shadow 0.2s ease;
    }
    .forms-grid-tile:hover .card {
        border-color: var(--bs-primary) !important;
        box-shadow: 0 0.35rem 1rem rgba(0, 0, 0, 0.18);
    }
    .forms-grid-tile:focus-visible .card {
        outline: 2px solid var(--bs-primary);
        outline-offset: 2px;
    }
</style>
@endpush
