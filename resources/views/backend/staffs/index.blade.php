@extends('layouts.backend')

@section('content')
    <div class="selectFilter" style="display:none; min-width: 200px;">
        <select id="getFilter">
            <option value="">All Roles</option>
            @foreach ($roles as $role)
                <option value="{{ $role->id }}">{{ $role->display_name }}</option>
            @endforeach
        </select>
    </div>

    <div class="row">
        <div class="col-sm-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-2 d-flex align-items-center justify-content-between">
                    <h5 class="mb-0 text-primary fw-bold">
                        <i class="ti ti-users me-2"></i>Staff Management
                    </h5>
                    <div class="d-flex gap-2">
                        <button class="btn btn-primary btn-action-add shadow-sm rounded-pill px-4" onclick="CRUD.open()">
                            <i class="ti ti-user-plus me-1"></i>Add Staff
                        </button>
                    </div>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-custom" id="dataTable">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Staff</th>
                                    <th>Email</th>
                                    <th>Roles</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            CRUD.setResource('staffs');
            const table = CRUD.loadDataTable([{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'name',
                    name: 'name',
                    render: (data, type, row) => {
                        return `
                            <div class="d-flex align-items-center">
                                <div class="avtar avtar-s bg-light-primary text-primary me-2">
                                    <i class="ti ti-user fs-6"></i>
                                </div>
                                <div>
                                    <div class="fw-bold text-dark">${data}</div>
                                    <div class="text-muted tiny-badge">ID: ${row.id}</div>
                                </div>
                            </div>
                        `;
                    }
                },
                {
                    data: 'email',
                    name: 'email'
                },
                {
                    data: 'roles',
                    name: 'roles',
                    orderable: false,
                    searchable: false,
                    render: (data) => {
                        if (!data) return '<span class="text-muted">No roles</span>';
                        return data.split(', ').map(role =>
                            `<span class="badge bg-light-primary text-primary rounded-pill me-1">${role}</span>`
                        ).join('');
                    }
                },
                CRUD.columnToggleStatus(),
                CRUD.columnActions()
            ]);

            if (typeof Choices !== 'undefined') {
                const filterSelect = new Choices('#getFilter', {
                    searchEnabled: true,
                    itemSelectText: '',
                    allowHTML: true,
                    shouldSort: false,
                    removeItemButton: true,
                    placeholder: true,
                    placeholderValue: 'All Roles',
                    fuseOptions: {
                        threshold: 0.1,
                        distance: 100
                    }
                });

                $('#getFilter').on('change', function() {
                    table.ajax.reload();
                });
            }
        });
    </script>
@endpush

@push('styles')
    <style>
        .avtar-s {
            width: 30px !important;
            height: 30px !important;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .card,
        .card-body,
        #dataTable_wrapper,
        .dataTables_wrapper,
        .table-responsive {
            overflow: visible !important;
        }
    </style>
@endpush
