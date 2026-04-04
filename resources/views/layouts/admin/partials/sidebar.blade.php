@php
    $modules = dynamic_sidebar();
    $user = auth()->user();

    $isAdmin = $user->hasRole('admin');

    $company = $user->company;

    $isAdmin = $user->hasRole(['admin']);

    $isProfileLocked =
        !$isAdmin && (
            !$company
            || (int) $company->is_profile_completed !== 1
            || (int) $company->is_profile_approved !== 1
        );

    $lockedModules = ['services.index', 'services.create', 'contractors.index', 'contractors.create', 'products.index', 'products.create'];
@endphp

<div class="sidebar-wrapper" data-sidebar-layout="stroke-svg">
    <div class="logo-wrapper">
        <a href="">
            <img class="img-fluid for-light" src="{{ asset('/images/logo.png') }}" alt="" style="max-width: 60px" />
            <img class="img-fluid for-dark" src="{{ asset('/images/logo.png') }}" alt=""
                style="max-width: 60px" />
        </a>
        <div class="back-btn"><i class="fa-solid fa-angle-left"></i></div>
        {{-- <div class="toggle-sidebar">
            <i class="status_toggle middle sidebar-toggle" data-feather="grid"></i>
        </div> --}}
    </div>
    {{-- <div class="logo-icon-wrapper">
        <a href="">
            <img class="img-fluid" src="{{ asset('/images/logo.png') }}" alt="" />
        </a>
    </div> --}}
    <nav class="sidebar-main">
        <div class="left-arrow" id="left-arrow">
            <i data-feather="arrow-left"></i>
        </div>
        <div id="sidebar-menu">
            <ul class="sidebar-links" id="simple-bar">
                <li class="back-btn">
                    {{-- <a href="">
                        <img class="img-fluid" src="{{ asset('images/logo.png') }}" alt="" />
                    </a> --}}
                    <div class="mobile-back text-end">
                        <span>Back</span><i class="fa-solid fa-angle-right ps-2" aria-hidden="true"></i>
                    </div>
                </li>
                <li class="pin-title sidebar-main-title">
                    <div>
                        <h6>Pinned</h6>
                    </div>
                </li>
                @foreach ($modules as $module)
                    @php
                        $hasChildren = $module->children && $module->children->count() > 0;
                        $isLocked = $isProfileLocked && in_array($module->route_name, $lockedModules);
                    @endphp

                    <li class="sidebar-list">
                        <i class="fa-solid fa-thumbtack"></i>
                        <a aria-disabled="{{ $isLocked ? 'true' : 'false' }}"
                            href="{{ $isLocked ? 'javascript:void(0)' : (Route::has($module->route_name) ? route($module->route_name) : '#') }}"
                            class="sidebar-link sidebar-title {{ $isLocked ? 'sidebar-disabled' : '' }} {{$hasChildren ? '' : 'link-nav'}}">
                            <span class="theme-icons">
                                <i class="{{ $module->icon }}"></i>
                            </span>

                            <span>{{ $module->name }}</span>

                            @if ($hasChildren)
                                <div class="according-menu">
                                    <i class="fa-solid fa-angle-right"></i>
                                </div>
                            @endif
                        </a>

                        @if ($hasChildren)
                            <ul class="sidebar-submenu">
                                @foreach ($module->children as $child)
                                    @php
                                        $childLocked = $isProfileLocked && in_array($child->route_name, $lockedModules);
                                    @endphp

                                    <li>
                                        <a aria-disabled="{{ $childLocked ? 'true' : 'false' }}"
                                            href="{{ $childLocked ? 'javascript:void(0)' : (Route::has($child->route_name) ? route($child->route_name) : '#') }}"
                                            class="{{ $childLocked ? 'sidebar-disabled' : '' }}">
                                            {{ $child->name }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </li>
                @endforeach

            </ul>
        </div>
        <div class="right-arrow" id="right-arrow">
            <i data-feather="arrow-right"></i>
        </div>
    </nav>
</div>
