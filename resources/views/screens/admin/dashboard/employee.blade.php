{{-- super admin dashboard --}}

@section('title', 'Dashboard')
@extends('layouts.admin.master')
@section('content')
    <div class="container-fluid row-gap-20 default-dashboard">
        <div class="row widget-grid">
            <div class="col-xxl-auto col-xl-3 col-sm-6 box-col-3">
                <a href="{{ route('checks.index') }}" class="text-decoration-none">
                <div class="card widget-1">
                    <div class="card-body">
                        <div class="widget-content">
                            <div class="widget-round secondary">
                                <div class="bg-round">
                                    <svg>
                                        <use href="{{ asset('/assets/libs/svg/icon-sprite.svg#c-revenue') }}"></use>
                                    </svg><svg class="half-circle svg-fill">
                                        <use href="{{ asset('/assets/libs/svg/icon-sprite.svg#halfcircle') }}">
                                        </use>
                                    </svg>
                                </div>
                            </div>
                            <div>
                                <span class="f-light">Checks</span>
                                <h4>
                                    <span class="">
                                        <span class="">{{ $myChecksCount ?? 0 }}</span>
                                    </span>
                                </h4>
                            </div>
                        </div>
                    </div>
                </div>
                </a>
            </div>
        </div>
    </div>
@endsection
