@section('title', 'Edit Check')
@extends('layouts.admin.master')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body text-muted py-5 text-center">
                        Edit check #{{ $checkId ?? '—' }} (placeholder).
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
