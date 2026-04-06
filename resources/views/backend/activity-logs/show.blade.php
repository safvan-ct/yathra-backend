<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label text-muted fw-bold">Actor</label>
        <div class="p-2 bg-light rounded shadow-sm">
            <strong>{{ $actorName }}</strong>
            <span class="badge bg-soft-info text-info rounded-pill ms-2">
                {{ class_basename($log->actor_type) }} #{{ $log->actor_id }}
            </span>
        </div>
    </div>

    <div class="col-md-6 mb-3">
        <label class="form-label text-muted fw-bold">Date & Time</label>
        <div class="p-2 bg-light rounded shadow-sm">
            {{ $log->created_at->format('M d, Y h:i:s A') }} ({{ $log->created_at->diffForHumans() }})
        </div>
    </div>

    <div class="col-md-6 mb-3">
        <label class="form-label text-muted fw-bold">Action</label>
        <div class="p-2 bg-light rounded shadow-sm">
            <span
                class="badge bg-{{ $log->action === 'delete' ? 'danger' : ($log->action === 'update' ? 'warning' : 'success') }} px-3 rounded-pill">
                {{ strtoupper($log->action) }}
            </span>
        </div>
    </div>

    <div class="col-md-6 mb-3">
        <label class="form-label text-muted fw-bold">Model</label>
        <div class="p-2 bg-light rounded shadow-sm">
            <strong>{{ $modelName }}</strong>
            @if ($log->model_type)
                <span class="badge bg-soft-secondary text-secondary rounded-pill ms-2">
                    {{ class_basename($log->model_type) }}
                </span>
            @endif
        </div>
    </div>

    <div class="col-md-6 mb-3">
        <label class="form-label text-muted fw-bold">IP Address</label>
        <div class="p-2 bg-light rounded shadow-sm">
            {{ $log->ip_address }}
        </div>
    </div>

    <div class="col-md-6 mb-3">
        <label class="form-label text-muted fw-bold">User Agent</label>
        <div class="p-2 bg-light rounded shadow-sm small text-wrap">
            {{ $log->user_agent }}
        </div>
    </div>

    @if ($log->changes)
        <div class="col-12 mt-3">
            <label class="form-label text-muted fw-bold">Data Changes</label>
            <div class="table-responsive bg-light p-3 rounded shadow-sm border">
                <table class="table table-sm table-bordered mb-0">
                    <thead class="bg-white">
                        <tr>
                            <th>Field</th>
                            <th>Before / Info</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($log->changes as $key => $value)
                            <tr>
                                <td class="fw-bold bg-white" style="width: 30%;">
                                    {{ ucwords(str_replace('_', ' ', $key)) }}</td>
                                <td>
                                    @if (is_array($value))
                                        @if (isset($value['old']) || isset($value['new']))
                                            <div class="d-flex flex-column gap-2">
                                                @isset($value['old'])
                                                    <div
                                                        class="p-2 bg-soft-danger text-danger rounded border border-danger border-opacity-10">
                                                        <small class="d-block text-uppercase fw-bold opacity-50 mb-1">
                                                            Old Value
                                                        </small>
                                                        {{ is_string($value['old']) ? $value['old'] : json_encode($value['old']) }}
                                                    </div>
                                                @endisset

                                                @isset($value['new'])
                                                    <div
                                                        class="p-2 bg-soft-success text-success rounded border border-success border-opacity-10">
                                                        <small class="d-block text-uppercase fw-bold opacity-50 mb-1">
                                                            New Value
                                                        </small>
                                                        {{ is_string($value['new']) ? $value['new'] : json_encode($value['new']) }}
                                                    </div>
                                                @endisset
                                            </div>
                                        @else
                                            <pre class="mb-0 small"><code>{{ json_encode($value, JSON_PRETTY_PRINT) }}</code></pre>
                                        @endif
                                    @else
                                        {{ $value }}
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
</div>
