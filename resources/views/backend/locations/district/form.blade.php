@if ($district)
    <input type="hidden" name="id" value="{{ $district->id }}">
@endif

<div class="row">
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

    <div class="col-md-6 mb-3">
        <label for="state_id" class="form-label fw-bold small text-uppercase">State</label>
        <select class="form-select choices-select" name="state_id" id="state_id" required>
            <option value="">Select State</option>
            @foreach ($states as $state)
                <option value="{{ $state->id }}" @if ($district && $district->state_id == $state->id) selected @endif>
                    {{ $state->name }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="col-md-6 mb-3">
        <label class="form-label fw-bold small text-uppercase">Status</label>
        <select class="form-select" name="is_active">
            <option value="1" @if ($district && $district->is_active) selected @endif>Active</option>
            <option value="0" @if ($district && !$district->is_active) selected @endif>Inactive</option>
        </select>
    </div>
</div>

<script>
    if (typeof Choices !== 'undefined') {
        const element = document.querySelector('.choices-select');
        new Choices(element, {
            searchEnabled: true,
            itemSelectText: '',
            placeholderValue: 'Select state...',
        });
    }
</script>
