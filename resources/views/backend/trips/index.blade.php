@extends('layouts.backend')

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-0 py-3 d-flex align-items-center justify-content-between">
                    <h5 class="mb-0 text-primary fw-bold">
                        <i class="ti ti-calendar-time me-2"></i>
                        @if (isset($bus))
                            Trips for: <span class="text-dark">{{ $bus->bus_name }} ({{ $bus->bus_number }})</span>
                        @else
                            Trip Management
                        @endif
                    </h5>

                    <div class="d-flex gap-2">
                        <a href="{{ route('backend.buses.index') }}"
                            class="btn btn-outline-secondary shadow-sm rounded-pill px-4">
                            <i class="ti ti-arrow-left me-1"></i> Back to Buses
                        </a>

                        <button class="btn btn-primary shadow-sm rounded-pill px-4"
                            onclick="openTripModal(0, '{{ $busId }}')">
                            <i class="ti ti-plus me-1"></i> Add Trip
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover" id="dataTable">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Route</th>
                                    <th>Route</th>
                                    <th>Route</th>
                                    <th>Departure</th>
                                    <th>Arrival</th>
                                    <th>Status/Active</th>
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
            CRUD.setResource('trips');

            const table = $('#dataTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('backend.trips.datatable') }}",
                    data: function(d) {
                        d.bus_id = "{{ $busId ?? '' }}";
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'origin_name',
                        name: 'origin_name'
                    },
                    {
                        data: 'destination_name',
                        name: 'destination_name'
                    },
                    {
                        data: 'route_name',
                        name: 'route_name'
                    },
                    {
                        data: 'departure',
                        name: 'departure'
                    },
                    {
                        data: 'arrival',
                        name: 'arrival'
                    },
                    {
                        data: 'status',
                        name: 'status',
                        className: "text-center",
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

        function openTripModal(id = 0, busId = '') {
            $("#crudTitle").html(id ? 'Edit Trip' : 'Create Trip');
            $("#crudBody").html("Loading...");

            let url = `/backend/trips/form/${id}`;
            if (busId) url += `?bus_id=${busId}`;

            $("#crudBody").load(url);
            $("#crudModal").modal("show");

            $("#crudForm").off("submit").on("submit", function(e) {
                e.preventDefault();
                CRUD.save("dataTable", busId ? `?bus_id=${busId}` : '');
            });
        }
    </script>
@endpush
