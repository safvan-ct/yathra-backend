@extends('layouts.admin')

@section('content')
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-0 py-3 d-flex align-items-center justify-content-between">
            <h5 class="mb-0 text-primary fw-bold">
                <i class="ti ti-train me-2"></i>Operators Hub
            </h5>
            <div class="d-flex gap-2">
                <button class="btn btn-primary shadow-sm rounded-pill px-4" onclick="CRUD.open()">
                    <i class="ti ti-plus me-1"></i> Add Operator
                </button>
            </div>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover" id="dataTable">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Type</th>
                            <th>Phone</th>
                            <th>Bus Count</th>
                            <th>Status/Active</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            CRUD.setResource('operators');

            const table = $('#dataTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('backend.operators.datatable') }}",
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'type',
                        name: 'type'
                    },
                    {
                        data: 'phone',
                        name: 'phone'
                    },
                    {
                        data: 'bus_count',
                        name: 'bus_count',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'status',
                        name: 'status',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    }
                ]
            });
        });
    </script>
@endpush
