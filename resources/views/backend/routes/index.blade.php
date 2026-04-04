@extends('layouts.admin')

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-0 py-3 d-flex align-items-center justify-content-between">
                    <h5 class="mb-0 text-primary fw-bold">
                        <i class="ti ti-route me-2"></i>Route Management
                    </h5>
                    <div class="d-flex gap-2">
                        <button class="btn btn-primary btn-action-add shadow-sm rounded-pill px-4" onclick="CRUD.open()">
                            <i class="ti ti-plus me-1"></i>Add Route
                        </button>
                    </div>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-custom"" id="dataTable">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Origin</th>
                                    <th>Destination</th>
                                    <th>Variant</th>
                                    <th>Distance (km)</th>
                                    <th>Stops</th>
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
            CRUD.setResource('transit-routes');
            const table = CRUD.loadDataTable([{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'origin_name',
                    name: 'origin_name',
                    className: 'fw-bold'
                },
                {
                    data: 'destination_name',
                    name: 'destination_name',
                    className: 'fw-bold'
                },
                {
                    data: 'path_signature',
                    name: 'path_signature',
                    render: (data) =>
                        `<span class="badge bg-light-primary text-primary px-3 rounded-pill">${data}</span>`
                },
                {
                    data: 'distance',
                    name: 'distance',
                    render: (data) => `<span class="text-muted fw-bold">${data} km</span>`
                },
                {
                    data: 'stops_count',
                    name: 'stops_count',
                    render: (data) =>
                        `<span class="badge bg-light-info text-info px-3 rounded-pill">${data} stops</span>`
                },
                CRUD.columnToggleStatus('is_active'),
                CRUD.columnActions()
            ]);

            // Custom search/filter if needed
        });
    </script>
@endpush
