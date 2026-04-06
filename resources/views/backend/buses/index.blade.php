@extends('layouts.backend')

@section('content')
    <div class="selectFilter" style="display:none; min-width: 250px;">
        <select id="operatorFilter" class="form-select border-0 bg-light selectFilter">
            <option value="">All Operators</option>
            @if (request('operator_id'))
                <option value="{{ request('operator_id') }}" selected>Operator #{{ request('operator_id') }}</option>
            @endif
        </select>
    </div>

    <div class="row">
        <div class="col-sm-12">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-0 py-3 d-flex align-items-center justify-content-between">
                    <h5 class="mb-0 text-primary fw-bold">
                        <i class="ti ti-bus me-2"></i>Bus Management
                    </h5>
                    <div class="d-flex gap-2">
                        <button class="btn btn-primary shadow-sm rounded-pill px-4" onclick="CRUD.open()">
                            <i class="ti ti-plus me-1"></i> Add Bus
                        </button>
                    </div>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover" id="dataTable">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Bus Name</th>
                                    <th>Bus Number</th>
                                    <th>Operator</th>
                                    <th>Category</th>
                                    <th>Seats</th>
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
            CRUD.setResource('buses');

            const table = $('#dataTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('backend.buses.datatable') }}",
                    data: function(d) {
                        d.operator_id = $('#operatorFilter').val();
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'bus_name',
                        name: 'bus_name'
                    },
                    {
                        data: 'bus_number',
                        name: 'bus_number'
                    },
                    {
                        data: 'operator_name',
                        name: 'operator_name'
                    },
                    {
                        data: 'category',
                        name: 'category'
                    },
                    {
                        data: 'total_seats',
                        name: 'total_seats'
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

            const operatorFilterEl = document.getElementById('operatorFilter');
            const operatorChoices = new Choices(operatorFilterEl, {
                searchEnabled: true,
                searchPlaceholderValue: "Search operator...",
                shouldSort: false,
                itemSelectText: '',
            });

            fetch("{{ route('backend.operators.search') }}?q=")
                .then(res => res.json())
                .then(data => {
                    const mapped = data.map(i => ({
                        value: i.value,
                        label: i.label
                    }));
                    mapped.unshift({
                        value: '',
                        label: 'All Operators'
                    });
                    operatorChoices.setChoices(mapped, 'value', 'label', true);

                    const currentVal = new URLSearchParams(window.location.search).get('operator_id');
                    if (currentVal) operatorChoices.setChoiceByValue(currentVal);
                });

            operatorFilterEl.addEventListener('search', function(event) {
                fetch("{{ route('backend.operators.search') }}?q=" + event.detail.value)
                    .then(res => res.json())
                    .then(data => {
                        const mapped = data.map(i => ({
                            value: i.value,
                            label: i.label
                        }));
                        mapped.unshift({
                            value: '',
                            label: 'All Operators'
                        });
                        operatorChoices.setChoices(mapped, 'value', 'label', true);
                    });
            });

            operatorFilterEl.addEventListener('change', function() {
                table.draw();
            });

            $(document).on('click', '.trips-btn', function() {
                const busId = $(this).data('id');
                window.location.href = "{{ route('backend.trips.index') }}?bus_id=" + busId;
            });
        });
    </script>
@endpush
