@if ($city)
    <input type="hidden" name="id" value="{{ $city->id }}">
@endif

<div class="row">
    <div class="col-md-4 mb-3">
        <label for="district_id" class="form-label fw-bold small text-uppercase">District</label>
        <select class="form-select choices-select" name="district_id" id="district_id" required>
            <option value="">Select District</option>
            @foreach ($districts as $district)
                <option value="{{ $district->id }}" selected>
                    {{ $district->name }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="col-md-4 mb-3">
        <label for="name" class="form-label fw-bold small text-uppercase">City Name</label>
        <input type="text" class="form-control" name="name" id="name" value="{{ $city ? $city->name : '' }}"
            placeholder="e.g. Kochi" required>
    </div>

    <div class="col-md-4 mb-3">
        <label for="local_name" class="form-label fw-bold small text-uppercase">Local Name</label>
        <input type="text" class="form-control" name="local_name" id="local_name"
            value="{{ $city ? $city->local_name : '' }}" placeholder="e.g. കൊച്ചി">
    </div>

    <div class="col-md-4 mb-3">
        <label for="code" class="form-label fw-bold small text-uppercase">Abbreviation / Code</label>
        <input type="text" class="form-control" name="code" id="code" value="{{ $city ? $city->code : '' }}"
            placeholder="e.g. KL 09" maxlength="5" required>
    </div>
</div>

<script>
    if (typeof CRUD !== 'undefined' && typeof Choices !== 'undefined') {
        const element = document.querySelector('.choices-select');
        CRUD.initAjaxChoices(element, "{{ route('backend.districts.search') }}", "Select district...");
    }
</script>
