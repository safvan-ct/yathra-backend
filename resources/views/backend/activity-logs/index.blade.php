@extends('layouts.backend')

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-0 py-3 d-flex align-items-center justify-content-between">
                    <h5 class="mb-0 text-primary fw-bold">
                        <i class="ti ti-history me-2"></i>Activity Logs
                    </h5>

                    <div class="d-flex gap-2">
                        <select id="actor_type_filter" class="form-select form-select-sm selectFilter" style="width: 150px;">
                            <option value="">All Actors</option>
                            <option value="App\Models\Staff">Staff</option>
                            <option value="App\Models\User">User</option>
                        </select>
                        <select id="action_filter" class="form-select form-select-sm selectFilter" style="width: 150px;">
                            <option value="">All Actions</option>
                            <option value="create">Create</option>
                            <option value="update">Update</option>
                            <option value="delete">Delete</option>
                            <option value="login">Login</option>
                        </select>
                    </div>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-custom" id="activityTable">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Date/Time</th>
                                    <th>Actor</th>
                                    <th>Action</th>
                                    <th>Model</th>
                                    <th>IP Address</th>
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
            const table = $('#activityTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('backend.activity-logs.datatable') }}",
                    data: function(d) {
                        d.actor_type = $('#actor_type_filter').val();
                        d.action = $('#action_filter').val();
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'created_at',
                        name: 'created_at'
                    },
                    {
                        data: 'actor',
                        name: 'actor_id'
                    },
                    {
                        data: 'action',
                        name: 'action'
                    },
                    {
                        data: 'model',
                        name: 'model_id'
                    },
                    {
                        data: 'ip_address',
                        name: 'ip_address'
                    },
                    {
                        data: 'action_btn',
                        name: 'action_btn',
                        orderable: false,
                        searchable: false
                    }
                ],
                order: [
                    [1, 'desc']
                ],
                language: {
                    search: "_INPUT_",
                    searchPlaceholder: "Search action or IP..."
                }
            });

            $('.selectFilter').on('change', function() {
                table.draw();
            });

            $(document).on('click', '.view-btn', function() {
                const id = $(this).data('id');
                const url = "{{ route('backend.activity-logs.show', ':id') }}".replace(':id', id);

                $('#crudTitle').text('Activity Log Details');
                $('#crudBody').html(
                    '<div class="text-center p-5"><div class="spinner-border text-primary" role="status"></div></div>'
                );
                $('#crudModal').modal('show');
                $('#crudForm button[type="submit"]').hide();

                $.get(url, function(response) {
                    $('#crudBody').html(response);
                });
            });

            $('#crudModal').on('hidden.bs.modal', function() {
                $('#crudForm button[type="submit"]').show();
            });
        });
    </script>
@endpush
