@extends('layouts.admin')

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="card border-0 shadow-sm overflow-hidden">
                <div class="card-header bg-white border-0 py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="mb-0 text-primary fw-bold">Role Hierarchy</h4>
                            <p class="text-muted small mb-0">Manage system access levels and permissions with
                                high-performance pagination</p>
                        </div>

                        <button class="btn btn-primary add-btn shadow-sm rounded-pill px-4" onclick="CRUD.open()">
                            <i class="ti ti-plus me-1"></i>Add New Role
                        </button>
                    </div>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-custom" id="dataTable">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Role Name</th>
                                    <th>Permissions</th>
                                    <th>Description</th>
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
            CRUD.setResource('roles');

            CRUD.loadDataTable([{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'display_name',
                    name: 'display_name',
                    render: (data, type, row) => {
                        return `
                            <div class="d-flex align-items-center">
                                <div class="avtar avtar-s bg-light-warning text-warning me-2">
                                    <i class="ti ti-shield-lock fs-6"></i>
                                </div>
                                <div class="fw-bold text-dark">${data}</div>
                            </div>
                        `;
                    }
                },
                {
                    data: 'permissions_count',
                    name: 'permissions_count',
                    orderable: false,
                    searchable: false,
                    render: (data) => {
                        return `
                            <span class="badge bg-light-primary text-primary rounded-pill px-3 py-2">
                                <i class="ti ti-key me-1"></i> ${data} Permissions
                            </span>
                        `;
                    }
                },
                {
                    data: 'description',
                    name: 'description',
                    render: (data) => `<span class="text-muted small">${data || '---'}</span>`
                },
                CRUD.columnActions()
            ]);
        });
    </script>
@endpush

@push('styles')
    <style>
        .avtar-s {
            width: 15px !important;
            height: 15px !important;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
    </style>
@endpush
