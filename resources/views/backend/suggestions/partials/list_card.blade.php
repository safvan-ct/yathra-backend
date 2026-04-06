@forelse($suggestions as $suggestion)
    @php
        $typeLabel = trim(strtolower($suggestion->proposed_for ?? ''));

        $typeIcons = [
            'trip' => ['icon' => 'ti ti-calendar-event', 'color' => 'primary'],
            'route' => ['icon' => 'ti ti-route', 'color' => 'success'],
            'route stop' => ['icon' => 'ti ti-map-pins', 'color' => 'info'],
            'stop' => ['icon' => 'ti ti-map-pin', 'color' => 'info'],
            'bus' => ['icon' => 'ti ti-bus', 'color' => 'warning'],
        ];

        $config = $typeIcons[$typeLabel] ?? ['icon' => 'ti ti-info-circle', 'color' => 'secondary'];
    @endphp

    <div class="card mb-2 suggestion-card cursor-pointer shadow-sm rounded-0"
        onclick="loadSuggestionDetails('{{ $suggestion->id }}')" data-id="{{ $suggestion->id }}">
        <div class="card-body p-2 px-3">
            <div class="d-flex align-items-center">
                <div class="icon-shape bg-soft-{{ $config['color'] }} text-{{ $config['color'] }} rounded me-3">
                    <i class="{{ $config['icon'] }} fs-3"></i>
                </div>

                <div class="flex-grow-1 min-width-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="mb-0 fw-bold text-dark text-truncate small">{{ $suggestion->proposed_for }}</h6>
                        <span
                            class="badge border bg-white
                            @if ($suggestion->status->value === 'Pending') text-warning
                            @elseif($suggestion->status->value === 'Approved') text-success
                            @elseif($suggestion->status->value === 'Rejected') text-danger
                            @else text-secondary @endif
                            smaller px-1 py-0">
                            {{ strtoupper($suggestion->status->value) }}
                        </span>
                    </div>

                    <div class="d-flex align-items-center mt-1 justify-content-between">
                        <div class="d-flex align-items-center text-muted" style="font-size: 0.7rem;">
                            <span class="text-dark fw-bold me-2">{{ $suggestion->user->name ?? 'User' }}</span>
                            <span class="opacity-75">#{{ substr($suggestion->id, -4) }}</span>
                        </div>

                        <div class="text-muted" style="font-size: 0.65rem;">
                            {{ $suggestion->created_at->diffForHumans(null, true) }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@empty
    <div class="text-center py-5">
        <div class="icon-shape bg-soft-light text-muted rounded-circle p-4 mb-2 mx-auto"
            style="width: 80px; height: 80px; display: flex; align-items: center; justify-content: center;">
            <i class="ti ti-inbox-off fs-2"></i>
        </div>
        <h6 class="fw-bold text-dark small">Queue Empty</h6>
        <p class="text-muted smaller px-5 mb-0">All caught up! No items to review.</p>
    </div>
@endforelse

<div class="mt-3 d-flex justify-content-center suggestion-pagination">
    {{ $suggestions->links('pagination::bootstrap-4') }}
</div>
