<!doctype html>
<html lang="en">

<head>
    <title>{{ config('app.name') }}</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="shortcut icon" href="{{ asset('img/favicon.ico') }}" type="image/x-icon">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap"
        id="main-font-link" />
    <link rel="stylesheet" href="{{ asset('backend/fonts/phosphor/duotone/style.css') }}" />
    <link rel="stylesheet" href="{{ asset('backend/fonts/tabler-icons.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('backend/fonts/feather.css') }}" />
    <link rel="stylesheet" href="{{ asset('backend/fonts/fontawesome.css') }}" />
    <link rel="stylesheet" href="{{ asset('backend/fonts/material.css') }}" />

    <link rel="stylesheet" href="{{ asset('backend/css/style.css') }}" id="main-style-link" />
    <link rel="stylesheet" href="{{ asset('backend/css/style-preset.css') }}" />
    <link rel="stylesheet" href="{{ asset('backend/css/custom.css') }}" />

    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css">

    <style>
        .choices__list--dropdown,
        .choices__list[aria-expanded] {
            z-index: 9999 !important;
        }

        .choices.is-focused .choices__inner,
        .choices.is-open .choices__inner {
            box-shadow: none !important;
        }

        /* DataTable Control Alignment */
        .selectFilter {
            margin-bottom: 0 !important;
            vertical-align: middle;
        }

        .dataTables_filter {
            margin-bottom: 0 !important;
        }

        .dataTables_filter label {
            margin-bottom: 0 !important;
            display: flex !important;
            align-items: center;
            gap: 5px;
        }

        .choices {
            margin-bottom: 0 !important;
            min-width: 200px;
        }

        .choices__inner {
            min-height: 38px !important;
            padding: 4px 10px !important;
            background: #fff !important;
            border-radius: 8px !important;
            border: 1px solid #dee2e6 !important;
        }
    </style>

    @stack('styles')
</head>

<body>
    <div class="loader-bg">
        <div class="loader-track">
            <div class="loader-fill"></div>
        </div>
    </div>

    <nav class="pc-sidebar">
        <div class="navbar-wrapper">
            <div class="m-header d-flex justify-content-center">
                <a href="{{ route('backend.dashboard') }}" class="b-brand text-primary">
                    <div class="fw-bold fs-2">
                        <span class="text-success">{{ config('app.name') }}</span>
                    </div>
                </a>
            </div>

            <div class="navbar-content">
                <ul class="pc-navbar">
                    <li class="pc-item">
                        <a href="{{ route('backend.dashboard') }}" class="pc-link">
                            <span class="pc-micon"><i class="ti ti-dashboard"></i></span>
                            <span class="pc-mtext">Dashboard</span>
                        </a>
                    </li>

                    <li class="pc-item pc-hasmenu">
                        <a href="javascript:void(0)" class="pc-link">
                            <span class="pc-micon"><i class="ti ti-package"></i></span>
                            <span class="pc-mtext">Staffs</span>
                            <span class="pc-arrow"><i data-feather="chevron-right"></i></span>
                        </a>
                        <ul class="pc-submenu">
                            <li class="pc-item">
                                <a class="pc-link" href="{{ route('backend.permissions.index') }}">Permissions</a>
                            </li>
                            <li class="pc-item">
                                <a class="pc-link" href="{{ route('backend.roles.index') }}">Roles</a>
                            </li>
                            <li class="pc-item">
                                <a class="pc-link" href="{{ route('backend.staffs.index') }}">Staffs</a>
                            </li>
                        </ul>
                    </li>

                    <li class="pc-item pc-hasmenu">
                        <a href="javascript:void(0)" class="pc-link">
                            <span class="pc-micon"><i class="ti ti-map-pin"></i></span>
                            <span class="pc-mtext">Locations</span>
                            <span class="pc-arrow"><i data-feather="chevron-right"></i></span>
                        </a>
                        <ul class="pc-submenu">
                            <li class="pc-item">
                                <a class="pc-link" href="{{ route('backend.states.index') }}">States</a>
                            </li>
                            <li class="pc-item">
                                <a class="pc-link" href="{{ route('backend.districts.index') }}">Districts</a>
                            </li>
                            <li class="pc-item">
                                <a class="pc-link" href="{{ route('backend.cities.index') }}">Cities</a>
                            </li>
                            <li class="pc-item">
                                <a class="pc-link" href="{{ route('backend.stations.index') }}">Stations</a>
                            </li>
                        </ul>
                    </li>

                    <li class="pc-item">
                        <a href="{{ route('backend.transit-routes.index') }}" class="pc-link">
                            <span class="pc-micon"><i class="ti ti-route"></i></span>
                            <span class="pc-mtext">Transit Routes</span>
                        </a>
                    </li>

                    <li
                        class="pc-item pc-hasmenu {{ Str::is('backend.trips.*', Route::currentRouteName()) ? 'active pc-trigger' : '' }}">
                        <a href="javascript:void(0)" class="pc-link">
                            <span class="pc-micon"><i class="ti ti-bus"></i></span>
                            <span class="pc-mtext">Fleet Management</span>
                            <span class="pc-arrow"><i data-feather="chevron-right"></i></span>
                        </a>
                        <ul class="pc-submenu">
                            <li class="pc-item">
                                <a class="pc-link" href="{{ route('backend.operators.index') }}">Operators Hub</a>
                            </li>
                            <li
                                class="pc-item {{ Str::is('backend.trips.*', Route::currentRouteName()) ? 'active' : '' }}">
                                <a class="pc-link" href="{{ route('backend.buses.index') }}">Buses & Trips</a>
                            </li>
                        </ul>
                    </li>

                    <li class="pc-item">
                        <a href="{{ route('backend.suggestions.index') }}" class="pc-link">
                            <span class="pc-micon"><i class="ti ti-inbox"></i></span>
                            <span class="pc-mtext">Suggestion Hub</span>
                        </a>
                    </li>

                    <li
                        class="pc-item {{ Str::is('backend.activity-logs.*', Route::currentRouteName()) ? 'active' : '' }}">
                        <a href="{{ route('backend.activity-logs.index') }}" class="pc-link">
                            <span class="pc-micon"><i class="ti ti-history"></i></span>
                            <span class="pc-mtext">Activity Logs</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <header class="pc-header">
        <div class="header-wrapper">
            <div class="me-auto pc-mob-drp">
                <ul class="list-unstyled">
                    <li class="pc-h-item header-mobile-collapse">
                        <a href="#" class="pc-head-link head-link-secondary ms-0" id="sidebar-hide">
                            <i class="ti ti-menu-2"></i>
                        </a>
                    </li>
                    <li class="pc-h-item pc-sidebar-popup">
                        <a href="#" class="pc-head-link head-link-secondary ms-0" id="mobile-collapse">
                            <i class="ti ti-menu-2"></i>
                        </a>
                    </li>
                    <li class="dropdown pc-h-item d-inline-flex d-md-none">
                        <a class="pc-head-link head-link-secondary dropdown-toggle arrow-none m-0"
                            data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="false"
                            aria-expanded="false">
                            <i class="ti ti-search"></i>
                        </a>
                        <div class="dropdown-menu pc-h-dropdown drp-search">
                            <form class="px-3">
                                <div class="mb-0 d-flex align-items-center">
                                    <i data-feather="search"></i>
                                    <input type="search" class="form-control border-0 shadow-none"
                                        placeholder="Search here. . ." />
                                </div>
                            </form>
                        </div>
                    </li>
                    <li class="pc-h-item d-none d-md-inline-flex">
                        <form class="header-search">
                            <i data-feather="search" class="icon-search"></i>
                            <input type="search" class="form-control" placeholder="Search here. . ." />
                            <button class="btn btn-light-secondary btn-search">
                                <i class="ti ti-adjustments-horizontal"></i>
                            </button>
                        </form>
                    </li>
                </ul>
            </div>

            <div class="ms-auto">
                <ul class="list-unstyled">
                    <li class="dropdown pc-h-item header-user-profile">
                        <a class="pc-head-link head-link-primary dropdown-toggle arrow-none me-0"
                            data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="false"
                            aria-expanded="false">
                            <img src="{{ asset('img/user.png') }}" alt="user-image" class="user-avtar" />
                            <span><i class="ti ti-settings"></i></span>
                        </a>

                        <div class="dropdown-menu dropdown-user-profile dropdown-menu-end pc-h-dropdown">
                            <div class="dropdown-header">
                                <h4>Hi, <span class="small text-muted">{{ Auth::user()->name }}</span></h4>
                                <p class="text-muted text-capitalize">Admin</p>
                                <hr />

                                <div class="profile-notification-scroll position-relative"
                                    style="max-height: calc(100vh - 280px)">
                                    <a href="{{ route('backend.profile.edit') }}" class="dropdown-item">
                                        <i class="ti ti-settings"></i>
                                        <span>Account Settings</span>
                                    </a>

                                    <form method="POST" action="{{ route('backend.logout') }}">
                                        @csrf

                                        <a href="javascript:void(0);" class="dropdown-item"
                                            onclick="event.preventDefault(); this.closest('form').submit();">
                                            <i class="ti ti-logout"></i>
                                            <span>Logout</span>
                                        </a>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </header>

    <div class="pc-container">
        <div class="pc-content">
            @yield('content')
        </div>
    </div>

    <div id="backdrop-loader" class="backdrop-loader">
        <div class="spinner"></div>
    </div>

    {{-- Global CRUD Modal --}}
    <div class="modal fade" id="crudModal">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 id="crudTitle"></h5>
                    <button class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <form id="crudForm">
                    <div class="modal-body" id="crudBody"></div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <!-- Required Js -->
    <script src="{{ asset('backend/js/plugins/popper.min.js') }}"></script>
    <script src="{{ asset('backend/js/plugins/simplebar.min.js') }}"></script>
    <script src="{{ asset('backend/js/plugins/bootstrap.min.js') }}"></script>
    {{-- <script src="{{ asset('backend/js/fonts/custom-font.js') }}"></script> --}}
    <script src="{{ asset('backend/js/script.js') }}"></script>
    <script src="{{ asset('backend/js/theme.js') }}"></script>
    <script src="{{ asset('backend/js/plugins/feather.min.js') }}"></script>


    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>

    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
    <script src="{{ asset('backend/js/custom.js') }}"></script>
    <script src="{{ asset('backend/js/crud.js') }}"></script>

    <script>
        window.logoPaths = {
            dark: "{{ asset('img/logo.png') }}",
            light: "{{ asset('img/logo.png') }}"
        };

        window.APP_URL = "{{ url('/') }}";

        preset_change('preset-1');
    </script>

    @stack('scripts')

    <script>
        $(document).ready(function() {
            const dataTableWrapper = $('#dataTable_wrapper .dataTables_filter');
            $('.selectFilter').insertBefore(dataTableWrapper).css('display', 'inline-block');
            if ($('.add-btn').length) {
                $('.add-btn').insertAfter(dataTableWrapper).css('display', 'inline-block');
            }

            $('#dataTable_wrapper .dataTables_filter').parent().css({
                display: 'flex',
                justifyContent: 'flex-end',
                gap: '15px',
                alignItems: 'center',
                flexWrap: 'wrap'
            });
        });

        function toggleActive(url) {
            const token = "{{ csrf_token() }}";

            updateStatus(url, token);
        }

        @if (session('success'))
            toastr.success("{{ session('success') }}");
        @endif

        @if (session('error'))
            toastr.error("{{ session('error') }}");
        @endif
    </script>
</body>

</html>
