@extends('layouts.client')

@php
    $employeeLabel = $employee->name
        ?: trim(collect([$employee->first_name ?? null, $employee->last_name ?? null])->filter()->join(' '));
@endphp

@section('title', 'Paystub')
@section('page-title', 'Paystub')
@section('page-description', $employeeLabel)

@push('styles')
    @include('client.employees.partials.paystub_css')
@endpush

@section('content')
    <div class="max-w-5xl mx-auto space-y-4 no-print">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <a href="{{ route('client.employees.show', $employee) }}"
                class="text-sm text-green-700 hover:text-green-900 font-medium">
                <i class="fas fa-arrow-left mr-1"></i> Back to employee
            </a>
            <div class="flex flex-wrap gap-2">
                <button type="button" onclick="window.print()"
                    class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-gray-700 bg-white hover:bg-gray-50 text-sm font-medium shadow-sm">
                    <i class="fas fa-print mr-2"></i> Print
                </button>
                <a href="{{ route('client.employees.show.payroll.pdf', [$employee, $payrollItem]) }}"
                    class="inline-flex items-center px-4 py-2 btn-gradient text-white rounded-lg text-sm font-medium shadow-sm hover:opacity-95">
                    <i class="fas fa-file-pdf mr-2"></i> Download PDF
                </a>
            </div>
        </div>
    </div>

    <div class="max-w-5xl mx-auto py-2">
        @include('client.employees.partials.paystub')
    </div>
@endsection
