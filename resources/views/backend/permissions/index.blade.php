@extends('layouts.backend')

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="card border-0 shadow-sm overflow-hidden">
                <div class="card-header bg-white border-0 py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="mb-0 text-primary fw-bold">Permission Hub</h4>
                            <p class="text-muted small mb-0">Manage system capabilities with high-performance pagination</p>
                        </div>
                        <button class="btn btn-primary add-btn shadow-sm rounded-pill px-4" onclick="CRUD.open()">
                            <i class="ti ti-plus me-1"></i>Add Permission
                        </button>
                    </div>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-custom" id="dataTable">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Permission</th>
                                    <th>Identifier</th>
                                    <th>Module</th>
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
            CRUD.setResource('permissions');

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
                        return `<div class="fw-bold text-dark">${data}</div>`;
                    }
                },
                {
                    data: 'name',
                    name: 'name',
                    render: (data) =>
                        `<code class="text-primary small bg-light-primary px-2 py-1 rounded">${data}</code>`
                },
                {
                    data: 'name',
                    name: 'module',
                    orderable: false,
                    searchable: false,
                    render: (data) => {
                        let parts = data.split(/[_-]/);
                        let module = parts.length > 1 ? parts[0] : 'General';
                        module = module.charAt(0).toUpperCase() + module.slice(1);

                        let icon = 'box';
                        if (module === 'Staff') icon = 'users';
                        else if (module === 'Bus') icon = 'bus';
                        else if (module === 'Route') icon = 'map-2';

                        return `
                            <span class="badge bg-light-secondary text-secondary rounded-pill px-3">
                                <i class="ti ti-${icon} me-1"></i> ${module}
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
