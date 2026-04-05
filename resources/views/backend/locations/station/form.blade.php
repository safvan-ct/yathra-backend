@if ($station)
    <input type="hidden" name="id" value="{{ $station->id }}">
@endif

<div class="row">
    <div class="col-md-4 mb-3">
        <label for="city_id" class="form-label fw-bold small text-uppercase">City</label>
        <select class="form-select choices-select" name="city_id" id="city_id" required>
            <option value="">Select City</option>
            @foreach ($cities as $city)
                <option value="{{ $city->id }}" selected>
                    {{ $city->name }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="col-md-4 mb-3">
        <label for="name" class="form-label fw-bold small text-uppercase">Station Name</label>
        <input type="text" class="form-control" name="name" id="name"
            value="{{ $station ? $station->name : '' }}" placeholder="e.g. Aluva" required>
    </div>

    <div class="col-md-4 mb-3">
        <label for="local_name" class="form-label fw-bold small text-uppercase">Local Name</label>
        <input type="text" class="form-control" name="local_name" id="local_name"
            value="{{ $station ? $station->local_name : '' }}" placeholder="e.g. ആലുവ">
    </div>

    <div class="col-md-4 mb-3">
        <label for="latitude" class="form-label fw-bold small text-uppercase">Latitude</label>
        <input type="number" step="any" class="form-control" name="latitude" id="latitude"
            value="{{ $station ? $station->latitude : '' }}" placeholder="e.g. 10.1076">
    </div>

    <div class="col-md-4 mb-3">
        <label for="longitude" class="form-label fw-bold small text-uppercase">Longitude</label>
        <input type="number" step="any" class="form-control" name="longitude" id="longitude"
            value="{{ $station ? $station->longitude : '' }}" placeholder="e.g. 76.3511">
    </div>
</div>

<script>
    if (typeof CRUD !== 'undefined' && typeof Choices !== 'undefined') {
        const element = document.querySelector('.choices-select');
        CRUD.initAjaxChoices(element, "{{ route('backend.cities.search') }}", "Select city...");
    }
</script>
