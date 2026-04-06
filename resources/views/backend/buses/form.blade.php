<input type="hidden" name="id" value="{{ $id ?? 0 }}">

<div class="row">
    <div class="col-md-4 mb-3">
        <label class="form-label">Operator</label>
        <select name="operator_id" id="operatorFormSelect" class="form-select" required>
            @if (isset($bus) && $bus->operator)
                <option value="{{ $bus->operator_id }}" selected>{{ $bus->operator->name }}</option>
            @endif
        </select>
    </div>

    <div class="col-md-4 mb-3">
        <label class="form-label">Bus Name</label>
        <input type="text" name="bus_name" class="form-control" value="{{ $bus->bus_name ?? '' }}" required>
    </div>

    <script>
        setTimeout(() => {
            const opSelect = document.getElementById('operatorFormSelect');
            if (opSelect && !opSelect.closest('.choices')) {
                CRUD.initAjaxChoices(opSelect, "{{ route('backend.operators.search') }}", "Search operator...");
            }
        }, 100);
    </script>

    <div class="col-md-4 mb-3">
        <label class="form-label">Bus Number</label>
        <input type="text" name="bus_number" class="form-control" value="{{ $bus->bus_number ?? '' }}"
            placeholder="e.g., KL 01 AB 1234" required>
    </div>
</div>

<div class="row">
    <div class="col-md-4 mb-3">
        <label class="form-label">Category</label>
        <select name="category" class="form-select">
            <option value="Ordinary" {{ isset($bus) && $bus->category == 'Ordinary' ? 'selected' : '' }}>
                Ordinary
            </option>
            <option value="AC" {{ isset($bus) && $bus->category == 'AC' ? 'selected' : '' }}>AC</option>
            <option value="Sleeper" {{ isset($bus) && $bus->category == 'Sleeper' ? 'selected' : '' }}>
                Sleeper
            </option>
            <option value="Seater" {{ isset($bus) && $bus->category == 'Seater' ? 'selected' : '' }}>
                Seater
            </option>
        </select>
    </div>

    <div class="col-md-4 mb-3">
        <label class="form-label">Color / Theme</label>
        @php $currentColor = $bus->bus_color ?? 'Blue'; @endphp
        <select name="bus_color" class="form-select">
            <option value="Blue" {{ $currentColor == 'Blue' ? 'selected' : '' }}>Blue</option>
            <option value="Green" {{ $currentColor == 'Green' ? 'selected' : '' }}>Green</option>
            <option value="Red" {{ $currentColor == 'Red' ? 'selected' : '' }}>Red</option>
            <option value="White" {{ $currentColor == 'White' ? 'selected' : '' }}>White</option>
        </select>
    </div>

    <div class="col-md-4 mb-3">
        <label class="form-label">Total Seats</label>
        <input type="number" name="total_seats" class="form-control" value="{{ $bus->total_seats ?? 40 }}" required
            min="1">
    </div>
</div>
