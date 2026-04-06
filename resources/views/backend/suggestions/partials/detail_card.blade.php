<div class="card border-0 shadow-sm h-100 fade-in">
    <div class="card-header bg-white border-bottom-0 py-4 d-flex justify-content-between align-items-center">
        <div>
            <h4 class="mb-1 fw-bold text-dark d-flex align-items-center flex-wrap gap-2">
                Moderating {{ $suggestion->proposed_for }}
                <span
                    class="badge ms-2
                    @if ($suggestion->status->value === 'Pending') bg-soft-warning text-warning border-warning
                    @elseif($suggestion->status->value === 'Approved') bg-soft-success text-success border-success
                    @elseif($suggestion->status->value === 'Rejected') bg-soft-danger text-danger border-danger
                    @else bg-soft-secondary text-secondary border-secondary @endif
                    border fw-bold p-1 px-2">
                    {{ strtoupper($suggestion->status->value) }}
                </span>
            </h4>

            <div class="d-flex align-items-center flex-wrap gap-3 mt-3">
                {{-- User Primary Info --}}
                <div class="d-flex align-items-center">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode($suggestion->user->name ?? 'U') }}&background=6366f1&color=fff"
                        class="rounded-circle me-2 border shadow-sm" width="35" height="35">
                    <div>
                        <div class="fw-bold text-dark small">{{ $suggestion->user->name ?? 'Unknown User' }}</div>
                        <div class="text-muted smaller d-flex align-items-center fw-bold">
                            <i class="ti ti-phone-call text-primary me-1"></i>
                            {{ $suggestion->user->phone ?? 'No Phone' }}
                        </div>
                    </div>
                </div>

                <div class="vr h-25 opacity-25 d-none d-sm-block" style="height: 25px !important; align-self: center;">
                </div>

                {{-- User Trust Profile --}}
                <div class="d-flex align-items-center">
                    @php
                        $trustColor = 'warning';
                        $level = strtolower($suggestion->user->trust_level ?? 'standard');

                        if ($level === 'high' || $level === 'verified') {
                            $trustColor = 'success';
                        }

                        if ($level === 'low') {
                            $trustColor = 'danger';
                        }
                    @endphp

                    <span
                        class="badge bg-soft-{{ $trustColor }} text-{{ $trustColor }} border border-{{ $trustColor }} rounded-pill px-3 py-1 smaller fw-bold">
                        <i class="ti ti-shield-check me-1"></i>
                        {{ strtoupper($suggestion->user->trust_level ?? 'STANDARD') }} TRUST
                    </span>

                    <span class="ms-2 smaller fw-bold text-muted bg-light px-2 py-1 rounded">
                        SCORE: {{ $suggestion->user->trust_score ?? 0 }}
                    </span>
                </div>

                <div class="vr h-25 opacity-25 d-none d-sm-block" style="height: 25px !important; align-self: center;">
                </div>

                {{-- Submission Metadata --}}
                <div class="text-muted smaller d-flex align-items-center">
                    <i class="ti ti-calendar me-1"></i>
                    <span>
                        {{ $suggestion->created_at->format('M d, Y') }}
                        <span class="fw-bold fs-7 ps-1">{{ $suggestion->created_at->format('h:i A') }}</span>
                    </span>
                </div>
            </div>
        </div>

        <div>
            <span class="badge bg-light-info text-info px-3 py-2 rounded-pill fs-6">
                <i class="ti ti-target me-1"></i> {{ ucfirst(str_replace('_', ' ', $suggestion->type->value)) }}
            </span>
        </div>
    </div>

    <div class="card-body p-0">
        {{-- Suggestion Summary Header (Compact) --}}
        <div class="px-4 py-3 bg-light d-flex justify-content-between align-items-center border-bottom">
            <div class="d-flex align-items-center">
                <span class="badge {{ $suggestion->type->value === 'update' ? 'bg-warning' : 'bg-success' }} me-2">
                    {{ str_replace('_', ' ', strtoupper($suggestion->type->value)) }}
                </span>
                <span class="text-muted small fw-bold">PROPOSED CHANGES</span>
            </div>
            <div class="text-muted smaller">ID: #{{ $suggestion->id }}</div>
        </div>

        <div class="p-4">
            <div class="row g-3">
                @if ($suggestion->proposed_for === 'Bus')
                    <div class="col-12">
                        <div class="bg-white rounded-3 border overflow-hidden shadow-sm">
                            <div class="px-4 py-4 bg-soft-warning border-bottom d-flex align-items-center gap-4">
                                <div class="icon-shape bg-white text-warning rounded-circle p-3 shadow-sm">
                                    <i class="ti ti-bus fs-2"></i>
                                </div>

                                <div class="flex-grow-1">
                                    <small class="text-muted d-block fw-bold smaller text-uppercase mb-1">
                                        Proposed Vehicle
                                    </small>

                                    <h3 class="mb-0 fw-bold text-dark">
                                        {{ $suggestion->proposed_data['bus_name'] ?? 'Untitled Bus' }}
                                    </h3>

                                    <small class="text-muted d-block mt-1">
                                        Operator:
                                        <strong>{{ $suggestion->proposed_data['operator_type'] ?? 'Unknown Operator' }}</strong>
                                        <small class="smaller">
                                            (#{{ $suggestion->proposed_data['operator_id'] ?? '?' }})
                                        </small>
                                    </small>
                                </div>

                                <div class="text-end">
                                    <small class="text-muted d-block fw-bold smaller text-uppercase mb-1">
                                        Plate Number
                                    </small>
                                    <div class="bg-dark text-white px-3 py-1 rounded fw-bold fs-5 shadow-sm border border-secondary"
                                        style="letter-spacing: 1px;">
                                        {{ $suggestion->proposed_data['bus_number'] ?? 'N/A' }}
                                    </div>
                                </div>
                            </div>

                            @php
                                $extraFields = array_diff_key(
                                    $suggestion->proposed_data,
                                    array_flip(['bus_name', 'bus_number', 'bus_number_code', 'operator_id']),
                                );
                            @endphp

                            @if (count($extraFields) > 0)
                                <div class="p-3 bg-light bg-opacity-10">
                                    <div class="row g-2">
                                        @foreach ($extraFields as $key => $value)
                                            <div class="col-md-4">
                                                <div class="p-3 bg-white border rounded">
                                                    <small
                                                        class="text-muted d-block fw-bold smaller text-uppercase mb-1">
                                                        {{ str_replace('_', ' ', $key) }}
                                                    </small>
                                                    <div class="fw-bold text-dark">{{ $value ?: '—' }}</div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                @elseif($suggestion->proposed_for === 'Stop')
                    <div class="col-12">
                        <div class="bg-white rounded-3 border overflow-hidden shadow-sm">
                            <div class="px-4 py-4 bg-soft-info border-bottom d-flex align-items-center gap-4">
                                <div class="icon-shape bg-white text-info rounded-circle p-3 shadow-sm">
                                    <i class="ti ti-map-pin fs-2"></i>
                                </div>

                                <div class="flex-grow-1">
                                    <small class="text-muted d-block fw-bold smaller text-uppercase mb-1">
                                        Proposed Stop
                                    </small>

                                    <h3 class="mb-0 fw-bold text-dark">
                                        {{ $suggestion->proposed_data['name'] ?? 'Untitled Station' }}
                                    </h3>

                                    @if (!empty($suggestion->proposed_data['local_name']))
                                        <div class="text-muted smaller fw-medium mt-1">
                                            {{ $suggestion->proposed_data['local_name'] }}
                                        </div>
                                    @endif
                                </div>

                                <div class="text-end">
                                    <small class="text-muted d-block fw-bold smaller text-uppercase mb-1">
                                        Station Code
                                    </small>
                                    <div class="badge bg-info text-white px-3 py-2 rounded fw-bold fs-6">
                                        {{ $suggestion->proposed_data['code'] ?? 'NEW' }}
                                    </div>
                                </div>
                            </div>

                            <div class="p-3 bg-light bg-opacity-10">
                                <div class="row g-2">
                                    <div class="col-12 mb-2">
                                        <div class="p-3 bg-white border rounded d-flex align-items-center">
                                            <i class="ti ti-world text-muted me-2"></i>
                                            <div
                                                class="d-flex align-items-center flex-wrap gap-2 text-muted smaller fw-bold">
                                                <span class="text-dark">{{ $metadata['state_id'] ?? 'State' }}</span>
                                                <i class="ti ti-chevron-right smaller"></i>
                                                <span class="text-dark">
                                                    {{ $metadata['district_id'] ?? 'District' }}
                                                </span>
                                                <i class="ti ti-chevron-right smaller"></i>
                                                <span class="text-dark">{{ $metadata['city_id'] ?? 'City' }}</span>
                                            </div>
                                        </div>
                                    </div>

                                    @if (isset($suggestion->proposed_data['latitude'], $suggestion->proposed_data['longitude']))
                                        <div class="col-12">
                                            <div class="p-3 bg-white border rounded">
                                                <small class="text-muted d-block fw-bold smaller text-uppercase mb-1">
                                                    GPS Coordinates
                                                </small>
                                                <div class="fw-bold text-info">
                                                    <i class="ti ti-gps me-1"></i>
                                                    {{ $suggestion->proposed_data['latitude'] ?? 00 }},
                                                    {{ $suggestion->proposed_data['longitude'] ?? 00 }}
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @elseif($suggestion->proposed_for === 'Route')
                    <div class="col-12">
                        <div class="bg-white rounded-3 border overflow-hidden shadow-sm">
                            <div class="px-4 py-3 bg-soft-success border-bottom">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div class="d-flex align-items-center">
                                        <i class="ti ti-map-2 text-success fs-3 me-2"></i>
                                        <h5 class="mb-0 fw-bold text-dark d-flex align-items-center">
                                            @if (isset($metadata['origin']))
                                                {{ $metadata['origin']->name }}
                                                <i class="ti ti-arrow-right mx-3 text-success opacity-50"></i>
                                                {{ $metadata['destination']->name ?? 'End Station' }}
                                            @else
                                                Route Proposal
                                            @endif
                                        </h5>
                                    </div>
                                    <span class="badge bg-white text-success border">NEW ROUTE</span>
                                </div>
                            </div>

                            <div class="p-3 bg-light bg-opacity-10">
                                <div class="row g-2">
                                    <div class="col-md-4">
                                        <div class="p-3 bg-white border rounded">
                                            <small class="text-muted d-block fw-bold smaller text-uppercase mb-1">
                                                Route Code
                                            </small>
                                            <div class="fw-bold text-dark fs-5">
                                                {{ $metadata['origin']->code ?? '--' }}_
                                                {{ $metadata['destination']->code ?? '--' }}_
                                                {{ $suggestion->proposed_data['path_signature'] ?? '--' }}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="p-3 bg-white border rounded">
                                            <small class="text-muted d-block fw-bold smaller text-uppercase mb-1">
                                                Total Distance
                                            </small>
                                            <div class="fw-bold text-success fs-5">
                                                {{ isset($suggestion->proposed_data['distance']) ? number_format($suggestion->proposed_data['distance']) : '0' }}
                                                <span class="smaller fw-normal text-muted">KM</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-4">
                                        <div class="p-3 bg-white border rounded">
                                            <small class="text-muted d-block fw-bold smaller text-uppercase mb-1">
                                                Path Signature
                                            </small>
                                            <code class="text-muted small d-block mt-1">
                                                {{ $suggestion->proposed_data['path_signature'] ?? 'Dynamic Path' }}
                                            </code>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @elseif ($suggestion->proposed_for === 'Trip')
                    <div class="col-12">
                        <div class="bg-white rounded-4 border shadow-sm overflow-hidden">
                            <div
                                class="px-4 py-3 bg-soft-primary border-bottom d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center">
                                    <i class="ti ti-route text-primary fs-4 me-2"></i>
                                    <h5 class="mb-0 fw-bold text-dark d-flex align-items-center">
                                        @if (isset($metadata['route']))
                                            {{ $metadata['route']->origin->name }}
                                            <i class="ti ti-arrow-right mx-2 text-muted opacity-50"></i>
                                            {{ $metadata['route']->destination->name }}
                                        @else
                                            Route #{{ $suggestion->proposed_data['route_id'] ?? '?' }}
                                        @endif
                                    </h5>
                                </div>
                                <span class="badge bg-white text-primary border small">TRIP CONTRIBUTION</span>
                            </div>

                            <div class="p-4 border-bottom">
                                <div class="row align-items-center g-3">
                                    <div class="col-md-5">
                                        <div class="d-flex align-items-center">
                                            <div class="icon-shape bg-light text-primary rounded p-2 me-3">
                                                <i class="ti ti-bus fs-5"></i>
                                            </div>
                                            <div>
                                                <small class="text-muted d-block fw-bold smaller text-uppercase">
                                                    Bus Detail
                                                </small>
                                                <h6 class="mb-0 fw-bold">
                                                    @if (isset($metadata['bus']))
                                                        {{ $metadata['bus']->bus_name }}
                                                        <span
                                                            class="text-muted fw-normal">({{ $metadata['bus']->bus_number }})
                                                        </span>
                                                    @else
                                                        Bus #{{ $suggestion->proposed_data['bus_id'] ?? '?' }}
                                                    @endif
                                                </h6>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-2 text-center d-none d-md-block">
                                        <div class="h-100 d-flex flex-column align-items-center">
                                            <div class="vr bg-light flex-grow-1" style="width: 2px;"></div>
                                        </div>
                                    </div>

                                    <div class="col-md-5">
                                        <div class="d-flex align-items-center">
                                            <div class="icon-shape bg-light text-primary rounded p-2 me-3">
                                                <i class="ti ti-clock-play fs-5"></i>
                                            </div>
                                            <div>
                                                <small
                                                    class="text-muted d-block fw-bold smaller text-uppercase">Timeline</small>
                                                <h6 class="mb-0 fw-bold text-primary">
                                                    {{ \Carbon\Carbon::parse($suggestion->proposed_data['departure_time'])->format('h:i A') ?? '--:--' }}
                                                    <i class="ti ti-minus text-muted mx-1"></i>
                                                    {{ \Carbon\Carbon::parse($suggestion->proposed_data['arrival_time'])->format('h:i A') ?? '--:--' }}
                                                </h6>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="px-4 py-3 bg-light">
                                <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                                    <div class="d-flex align-items-center gap-1">
                                        <small class="text-muted fw-bold me-2 smaller">ACTIVE ON</small>
                                        @if (!empty($metadata['days_array']) && count($metadata['days_array']) === 7)
                                            <div class="d-flex gap-1">
                                                @foreach (['S', 'M', 'T', 'W', 'T', 'F', 'S'] as $idx => $label)
                                                    @php $isActive = $metadata['days_array'][$idx] == 1; @endphp
                                                    <div class="rounded d-flex align-items-center justify-content-center fw-bold"
                                                        style="width: 22px; height: 22px; font-size: 0.6rem;
                                                                background: {{ $isActive ? 'var(--bs-primary)' : '#e2e8f0' }};
                                                                color: {{ $isActive ? '#fff' : '#94a3b8' }};">
                                                        {{ $label }}
                                                    </div>
                                                @endforeach
                                            </div>
                                            <small class="ms-1 text-primary fw-bold smaller">
                                                ({{ $metadata['day_name'] }})
                                            </small>
                                        @else
                                            <span class="text-dark fw-bold small">
                                                {{ $metadata['day_name'] ?? 'Not set' }}
                                            </span>
                                        @endif
                                    </div>
                                    <div class="text-muted smaller">
                                        Route Path:
                                        <span class="fw-bold">
                                            {{ $metadata['route']->path_signature ?? 'Undefined' }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @elseif($suggestion->proposed_for === 'Route Stop')
                    <div class="col-12">
                        <div class="bg-white rounded-4 border shadow-sm overflow-hidden">
                            <div
                                class="px-3 py-2 bg-soft-light border-bottom d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center">
                                    <i class="ti ti-route text-muted me-2 small"></i>
                                    <span class="text-muted smaller fw-bold me-2">ROUTE:</span>
                                    <span class="fw-bold text-dark smaller">
                                        {{ isset($metadata['route']) ? $metadata['route']->origin->name . ' → ' . $metadata['route']->destination->name : 'Route #' . ($suggestion->proposed_data['route_id'] ?? '?') }}
                                    </span>
                                </div>
                                <div class="badge bg-soft-primary text-primary smaller">SEQUENCE INSERTION</div>
                            </div>

                            <div
                                class="px-3 py-2 bg-light border-bottom d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center">
                                    <i class="ti ti-arrow-right-tail text-muted me-2"></i>
                                    <span class="text-muted small fw-bold">AFTER:</span>
                                    <span class="ms-2 fw-bold text-dark small">
                                        @if (isset($metadata['before_stop']))
                                            {{ $metadata['before_stop']->station->name }}
                                            <span class="text-muted fw-normal smaller ps-1">
                                                (Seq: {{ $metadata['before_stop']->stop_sequence }},
                                                {{ number_format($metadata['before_stop']->distance_from_origin) }}Km)
                                            </span>
                                        @else
                                            <span class="text-muted italic">Route Start</span>
                                        @endif
                                    </span>
                                </div>
                                <div class="text-primary smaller fw-bold">
                                    INSERTING AT NODE
                                    #{{ $suggestion->proposed_data['stop_sequence'] ?? '?' }}
                                </div>
                            </div>

                            <div class="p-4 bg-soft-primary bg-opacity-10">
                                <div class="row align-items-center justify-content-center">
                                    <div class="col-md-8 text-center text-md-start">
                                        <div
                                            class="d-flex align-items-center mb-3 justify-content-center justify-content-md-start">
                                            <div
                                                class="icon-shape bg-primary text-white rounded-circle p-2 me-3 shadow">
                                                <i class="ti ti-map-pin-plus fs-4"></i>
                                            </div>
                                            <div>
                                                <small class="text-primary d-block fw-bold smaller text-uppercase"
                                                    style="letter-spacing: 1px;">Confirm Proposed Stop</small>
                                                <h2 class="mb-0 fw-bold text-dark">
                                                    @if (isset($metadata['station']))
                                                        {{ $metadata['station']->name }}
                                                    @else
                                                        Station #{{ $suggestion->proposed_data['station_id'] ?? '?' }}
                                                    @endif
                                                </h2>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div
                                            class="bg-white p-3 rounded-3 shadow-sm border-start border-4 border-primary">
                                            <div class="d-flex justify-content-between mb-2">
                                                <span class="text-muted small fw-bold">SEQUENCE</span>
                                                <span class="fw-bold text-primary">
                                                    {{ $suggestion->proposed_data['stop_sequence'] ?? '?' }}
                                                </span>
                                            </div>
                                            <div class="d-flex justify-content-between">
                                                <span class="text-muted small fw-bold text-uppercase">
                                                    DISTANCE FROM
                                                    ({{ isset($metadata['route']) ? $metadata['route']->origin->name : 'ORIGIN' }})
                                                </span>
                                                <span class="fw-bold text-primary">
                                                    {{ $suggestion->proposed_data['distance_from_origin'] ?? '0' }}
                                                    <small class="smaller">KM</small>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    @foreach ($suggestion->proposed_data as $key => $value)
                        @if (!is_array($value))
                            <div class="col-md-4">
                                <div class="p-3 bg-white border rounded rounded-3 shadow-none">
                                    <small class="text-muted d-block fw-bold smaller text-uppercase mb-1"
                                        style="letter-spacing: 0.5px;">
                                        {{ str_replace('_', ' ', $key) }}
                                    </small>
                                    <div class="fw-bold text-dark">
                                        @if (isset($metadata[$key]))
                                            {{ $metadata[$key] }}
                                            <span class="text-muted smaller fw-normal">(#{{ $value }})</span>
                                        @else
                                            {{ $value ?: '—' }}
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="col-12">
                                <label
                                    class="text-muted smaller fw-bold mb-1">{{ str_replace('_', ' ', Str::title($key)) }}</label>
                                <div class="bg-light p-2 rounded border overflow-auto" style="max-height: 150px;">
                                    <pre class="mb-0 smaller text-dark" style="white-space: pre-wrap; font-size: 0.75rem;">
                                        {{ json_encode($value, JSON_PRETTY_PRINT) }}
                                    </pre>
                                </div>
                            </div>
                        @endif
                    @endforeach
                @endif

                @if (isset($similarItems) && $similarItems->isNotEmpty())
                    <div class="col-12 mt-2">
                        <div class="p-3 bg-soft-danger bg-opacity-10 border border-danger-subtle rounded-3 shadow-sm">
                            <h6
                                class="text-uppercase text-danger fw-bold mb-3 small tracking-wide d-flex align-items-center">
                                <i class="ti ti-alert-triangle-filled me-2 fs-5"></i>
                                {{ $title ?? 'Potential Duplicates Found' }}
                            </h6>

                            <div class="d-flex flex-wrap gap-2">
                                @foreach ($similarItems as $sim)
                                    <div
                                        class="bg-white border border-danger-subtle rounded-3 p-2 px-3 d-flex align-items-center shadow-sm">
                                        <div class="icon-shape bg-danger text-white rounded-circle p-1 me-2"
                                            style="width: 24px; height: 24px; display: flex; align-items: center; justify-content: center;">
                                            <i class="ti {{ $icon ?? 'ti-map-pin' }} smaller"></i>
                                        </div>
                                        <div>
                                            <span
                                                class="d-block text-dark fw-bold smaller">{{ $sim['primary'] ?? '' }}</span>
                                            @if (!empty($sim['secondary']))
                                                <span class="d-block text-muted" style="font-size: 0.65rem;">
                                                    {{ $sim['secondary'] }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif

                @if ($suggestion->review_note)
                    <div class="col-12 mt-2">
                        <div class="p-3 bg-soft-warning bg-opacity-10 border border-warning-subtle rounded-3">
                            <h6 class="fw-bold text-warning mb-2 d-flex align-items-center small">
                                <i class="ti ti-message-2-share me-2 fs-5"></i>
                                MODERATOR NOTE
                            </h6>
                            <p class="mb-1 text-dark fw-medium small">{{ $suggestion->review_note }}</p>
                            <div class="d-flex align-items-center mt-2 pt-2 border-top border-warning-subtle">
                                <div class="avatar avatar-xs bg-warning text-white rounded-circle me-2 d-flex align-items-center justify-content-center"
                                    style="width: 20px; height: 20px; font-size: 0.6rem;">
                                    {{ substr($suggestion->admin->name ?? 'A', 0, 1) }}
                                </div>
                                <small class="text-muted smaller fw-bold">
                                    — {{ $suggestion->admin->name ?? 'System Admin' }}
                                </small>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        @if ($suggestion->status->value === 'Pending')
            <div class="card-footer bg-white border-top-0 py-4 px-4 d-flex justify-content-end gap-3 rounded-bottom">
                <button class="btn btn-outline-danger px-4 rounded-pill shadow-sm"
                    onclick="openReviewModal('{{ $suggestion->id }}', 'Rejected')">
                    <i class="ti ti-x me-1"></i> Reject
                </button>
                <button class="btn btn-outline-warning px-4 rounded-pill shadow-sm"
                    onclick="openReviewModal('{{ $suggestion->id }}', 'Flagged')">
                    <i class="ti ti-flag me-1"></i> Flag Spam
                </button>
                <button class="btn btn-success px-5 rounded-pill shadow-sm"
                    onclick="openReviewModal('{{ $suggestion->id }}', 'Approved')">
                    <i class="ti ti-check me-1"></i> Approve & Apply
                </button>
            </div>
        @endif
    </div>
