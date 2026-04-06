<input type="hidden" name="id" value="{{ $id ?? 0 }}">

<div class="row">
    <div class="col-md-4 mb-3">
        <label class="form-label">Bus</label>
        <select name="bus_id" class="form-select @if ($busId) readonly-select @endif" required>
            @foreach ($buses as $bus)
                <option value="{{ $bus->id }}"
                    {{ $busId == $bus->id || (isset($trip) && $trip->bus_id == $bus->id) ? 'selected' : '' }}>
                    {{ $bus->bus_name }} ({{ $bus->bus_number }})
                </option>
            @endforeach
        </select>
    </div>

    <div class="col-md-4 mb-3">
        <label class="form-label">Route</label>
        <select name="route_id" class="form-select" required>
            @foreach ($routes as $route)
                <option value="{{ $route->id }}"
                    {{ isset($trip) && $trip->route_id == $route->id ? 'selected' : '' }}>
                    {{ $route->route_code }} ({{ $route->origin->name }} - {{ $route->destination->name }})
                </option>
            @endforeach
        </select>
    </div>

    <div class="col-md-2 mb-3">
        <label class="form-label">Departure Time</label>
        <input type="time" name="departure_time" class="form-control" value="{{ $trip->departure_time ?? '' }}"
            required>
    </div>

    <div class="col-md-2 mb-3">
        <label class="form-label">Arrival Time</label>
        <input type="time" name="arrival_time" class="form-control" value="{{ $trip->arrival_time ?? '' }}"
            required>
    </div>
</div>

<style>
    .readonly-select {
        pointer-events: none;
        background-color: #f8f9fa;
        color: #6c757d;
    }
</style>
