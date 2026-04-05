@if ($district)
    <input type="hidden" name="id" value="{{ $district->id }}">
@endif

<div class="row">
    <div class="col-md-4 mb-3">
        <label for="state_id" class="form-label fw-bold small text-uppercase">State</label>
        <select class="form-select choices-select" name="state_id" id="state_id" required>
            <option value="">Select State</option>
            @foreach ($states as $state)
                <option value="{{ $state->id }}" selected>
                    {{ $state->name }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="col-md-4 mb-3">
        <label for="name" class="form-label fw-bold small text-uppercase">District Name</label>
        <input type="text" class="form-control" name="name" id="name"
            value="{{ $district ? $district->name : '' }}" placeholder="e.g. Ernakulam" required>
    </div>

    <div class="col-md-4 mb-3">
        <label for="local_name" class="form-label fw-bold small text-uppercase">Local Name</label>
        <input type="text" class="form-control" name="local_name" id="local_name"
            value="{{ $district ? $district->local_name : '' }}" placeholder="e.g. എറണാകുളം">
    </div>

    <div class="col-md-4 mb-3">
        <label for="code" class="form-label fw-bold small text-uppercase">Abbreviation / Code</label>
        <input type="text" class="form-control" name="code" id="code"
            value="{{ $district ? $district->code : '' }}" placeholder="e.g. KL" maxlength="5" required>
    </div>
</div>

<script>
    if (typeof CRUD !== 'undefined' && typeof Choices !== 'undefined') {
        const element = document.querySelector('.choices-select');
        CRUD.initAjaxChoices(element, "{{ route('backend.states.search') }}", "Select state...");
    }
</script>
