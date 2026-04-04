@if ($route)
    <input type="hidden" name="id" value="{{ $route->id }}">
@endif

<div class="row">
    <!-- Basic Info -->
    <div class="col-md-6 mb-3">
        <label for="origin_id" class="form-label fw-bold small text-uppercase">Origin Station</label>
        <select class="form-select choices-select" name="origin_id" id="origin_id" required>
            <option value="">Select Origin</option>
            @foreach ($stations as $station)
                <option value="{{ $station->id }}" @if ($route && $route->origin_id == $station->id) selected @endif>
                    {{ $station->name }} ({{ $station->city->name ?? 'N/A' }})
                </option>
            @endforeach
        </select>
    </div>

    <div class="col-md-6 mb-3">
        <label for="destination_id" class="form-label fw-bold small text-uppercase">Destination Station</label>
        <select class="form-select choices-select" name="destination_id" id="destination_id" required>
            <option value="">Select Destination</option>
            @foreach ($stations as $station)
                <option value="{{ $station->id }}" @if ($route && $route->destination_id == $station->id) selected @endif>
                    {{ $station->name }} ({{ $station->city->name ?? 'N/A' }})
                </option>
            @endforeach
        </select>
    </div>

    <div class="col-md-4 mb-3">
        <label for="variant" class="form-label fw-bold small text-uppercase">Route Variant (Via/Code)</label>
        <input type="text" class="form-control" name="variant" id="variant"
            value="{{ $route ? $route->path_signature : 'DIRECT' }}" placeholder="e.g. VIA ALUVA, BYPASS" required>
    </div>

    <div class="col-md-4 mb-3">
        <label for="distance" class="form-label fw-bold small text-uppercase">Total Distance (km)</label>
        <input type="number" step="0.01" class="form-control" name="distance" id="distance"
            value="{{ $route ? $route->distance : '0' }}" required>
    </div>

    <div class="col-md-4 mb-3 d-none">
        <label class="form-label fw-bold small text-uppercase">Status</label>
        <select class="form-select" name="is_active">
            <option value="1" @if ($route && $route->is_active) selected @endif>Active</option>
            <option value="0" @if ($route && !$route->is_active) selected @endif>Inactive</option>
        </select>
    </div>

    <!-- Stops Management -->
    <div class="col-12 mt-4">
        <div class="d-flex align-items-center justify-content-between mb-3 border-bottom pb-2">
            <h6 class="mb-0 fw-bold text-uppercase small text-primary"><i class="ti ti-list-numbers me-2"></i>Route
                Stops (Nodes)</h6>
            <button type="button" class="btn btn-sm btn-outline-primary rounded-pill" id="addStopBtn">
                <i class="ti ti-plus me-1"></i>Add Stop
            </button>
        </div>

        <div class="table-responsive">
            <table class="table table-sm table-bordered" id="stopsTable">
                <thead class="bg-light">
                    <tr>
                        <th style="width: 50px;">Seq</th>
                        <th>Station</th>
                        <th style="width: 150px;">Dist from Start (km)</th>
                        <th style="width: 80px;">Active</th>
                        <th style="width: 50px;"></th>
                    </tr>
                </thead>

                <tbody id="stopsBody">
                    @if ($route && $route->nodes->count() > 0)
                        @foreach ($route->nodes as $index => $node)
                            <tr class="stop-row" data-index="{{ $index }}">
                                <td>
                                    <span class="seq-text">{{ $node->stop_sequence }}</span>
                                    <input type="hidden" name="nodes[{{ $index }}][stop_sequence]"
                                        class="seq-input" value="{{ $node->stop_sequence }}">
                                    <input type="hidden" name="nodes[{{ $index }}][id]"
                                        value="{{ $node->id }}">

                                </td>
                                <td>
                                    <select class="form-select station-select"
                                        name="nodes[{{ $index }}][station_id]" required>
                                        <option value="">Select Station</option>
                                        @foreach ($stations as $station)
                                            <option value="{{ $station->id }}"
                                                @if ($node->station_id == $station->id) selected @endif>
                                                {{ $station->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <input type="number" step="0.01" class="form-control form-control-sm"
                                        name="nodes[{{ $index }}][distance_from_origin]"
                                        value="{{ $node->distance_from_origin }}" required>
                                </td>

                                <td class="text-center">
                                    <div class="form-check form-switch d-inline-block">
                                        <input type="hidden" name="nodes[{{ $index }}][is_active]"
                                            value="0">
                                        <input class="form-check-input" type="checkbox"
                                            name="nodes[{{ $index }}][is_active]" value="1"
                                            @if ($node->is_active) checked @endif>
                                    </div>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-link text-danger remove-stop-btn">
                                        <i class="ti ti-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        @endforeach

                    @endif

                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        let stopIndex = $('#stopsBody .stop-row').length;

        function updateSequences() {
            $('#stopsBody .stop-row').each(function(index) {
                const seq = index + 1;
                $(this).find('.seq-text').text(seq);
                $(this).find('.seq-input').val(seq);
            });
        }


        $('#addStopBtn').on('click', function() {
            const row = `
                <tr class="stop-row" data-index="${stopIndex}">
                    <td>
                        <span class="seq-text"></span>
                        <input type="hidden" name="nodes[${stopIndex}][stop_sequence]" class="seq-input">
                    </td>
                    <td>
                        <select class="form-select station-select" name="nodes[${stopIndex}][station_id]" required>
                            <option value="">Select Station</option>
                        </select>
                    </td>
                    <td>
                        <input type="number" step="0.01" class="form-control form-control-sm" name="nodes[${stopIndex}][distance_from_origin]" value="0" required>
                    </td>
                    <td class="text-center">
                        <div class="form-check form-switch d-inline-block">
                            <input type="hidden" name="nodes[${stopIndex}][is_active]" value="0">
                            <input class="form-check-input" type="checkbox" name="nodes[${stopIndex}][is_active]" value="1" checked>
                        </div>
                    </td>
                    <td>
                        <button type="button" class="btn btn-sm btn-link text-danger remove-stop-btn"><i class="ti ti-trash"></i></button>
                    </td>
                </tr>
            `;

            $('#stopsBody').append(row);

            const newSelect = $('#stopsBody').find(`[name="nodes[${stopIndex}][station_id]"]`)[0];
            if (typeof CRUD !== 'undefined') {
                CRUD.initAjaxChoices(newSelect, "{{ route('backend.stations.search') }}",
                    "Select station...");
            }

            stopIndex++;
            updateSequences();
        });


        $(document).on('click', '.remove-stop-btn', function() {
            $(this).closest('tr').remove();
            updateSequences();
        });

        // Drag and drop sorting
        $("#stopsBody").sortable({
            update: function(event, ui) {
                updateSequences();
            }
        });

        // Initialize existing choices
        document.querySelectorAll('.choices-select, .station-select').forEach(element => {
            if (typeof CRUD !== 'undefined') {
                CRUD.initAjaxChoices(element, "{{ route('backend.stations.search') }}",
                    "Select station...");
            }
        });
    });
</script>
