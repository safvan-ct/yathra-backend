@extends('layouts.admin')

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-0 py-3 d-flex align-items-center justify-content-between">
                    <h5 class="mb-0 text-primary fw-bold">
                        <i class="ti ti-map me-2"></i>State Management
                    </h5>
                    <div class="d-flex gap-2">
                        <button class="btn btn-outline-primary shadow-sm rounded-pill px-4" data-bs-toggle="modal"
                            data-bs-target="#importModal">
                            <i class="ti ti-file-import me-1"></i>Bulk Import
                        </button>
                        <button class="btn btn-primary btn-action-add shadow-sm rounded-pill px-4" onclick="CRUD.open()">
                            <i class="ti ti-plus me-1"></i>Add State
                        </button>
                    </div>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-custom" id="dataTable">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Local Name</th>
                                    <th>Code</th>
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

    {{-- Import Modal --}}
    <div class="modal fade" id="importModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Bulk Import States</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('backend.states.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="alert alert-warning small border-0 shadow-none">
                            <i class="ti ti-alert-circle me-1"></i>Upload a CSV file with headers: <strong>name, local_name,
                                code</strong>.
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Select CSV File</label>
                            <input type="file" name="file" class="form-control" accept=".csv" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary px-4">Upload & Preview</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            CRUD.setResource('states');
            CRUD.loadDataTable([{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'name',
                    name: 'name',
                    className: 'fw-bold text-dark'
                },
                {
                    data: 'local_name',
                    name: 'local_name'
                },
                {
                    data: 'code',
                    name: 'code',
                    render: (data) =>
                        `<span class="badge bg-light-info text-info rounded-pill px-3">${data}</span>`
                },
                CRUD.columnToggleStatus('is_active'),
                CRUD.columnActions()
            ]);
        });
    </script>
@endpush
