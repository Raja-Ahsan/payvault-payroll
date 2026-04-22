@section('title', 'Checks')
@extends('layouts.admin.master')
@section('content')
    <div class="container-fluid user-list-wrapper">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header card-no-border text-end">
                        <a class="btn btn-primary f-w-500" href="{{ route('checks.create') }}"><i class="fa-solid fa-plus pe-2"></i>Create check</a>
                    </div>
                    <div class="card-body pt-0 px-0">
                        <div class="table-responsive custom-scrollbar">
                            <table class="table mb-0">
                                <thead>
                                    <tr>
                                        <th><span class="c-o-light f-w-600">Pay date</span></th>
                                        <th><span class="c-o-light f-w-600">Employee</span></th>
                                        <th><span class="c-o-light f-w-600">Check #</span></th>
                                        <th><span class="c-o-light f-w-600">Gross</span></th>
                                        <th><span class="c-o-light f-w-600">Net pay</span></th>
                                        <th><span class="c-o-light f-w-600">Actions</span></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($checks as $row)
                                        @php
                                            $emp = $row->employee;
                                            $ln = trim((string) ($emp->last_name ?? ''));
                                            $fn = trim((string) ($emp->first_name ?? ''));
                                            $empLabel = $ln !== '' && $fn !== '' ? $ln . ', ' . $fn : ($ln !== '' ? $ln : ($fn !== '' ? $fn : '—'));
                                        @endphp
                                        <tr>
                                            <td>{{ optional($row->pay_date)->format('M j, Y') }}</td>
                                            <td>{{ $empLabel }}</td>
                                            <td>{{ $row->check_number }}</td>
                                            <td>{{ number_format((float) $row->gross_total, 2) }}</td>
                                            <td>{{ number_format((float) $row->net_pay, 2) }}</td>
                                            <td>
                                                <a class="square-white" href="{{ route('checks.show', $row) }}" title="View">
                                                    <span><i class="fa-solid fa-eye"></i></span>
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center text-muted py-4">No checks yet.</td>
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
