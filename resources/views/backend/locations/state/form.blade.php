@if ($state)
    <input type="hidden" name="id" value="{{ $state->id }}">
@endif

<div class="row">
    <div class="col-md-4 mb-3">
        <label for="name" class="form-label fw-bold small text-uppercase">State Name</label>
        <input type="text" class="form-control" name="name" id="name" value="{{ $state ? $state->name : '' }}"
            placeholder="e.g. Kerala" required>
    </div>

    <div class="col-md-4 mb-3">
        <label for="local_name" class="form-label fw-bold small text-uppercase">Local Name</label>
        <input type="text" class="form-control" name="local_name" id="local_name"
            value="{{ $state ? $state->local_name : '' }}" placeholder="e.g. കേരളം">
    </div>

    <div class="col-md-4 mb-3">
        <label for="code" class="form-label fw-bold small text-uppercase">Abbreviation / Code</label>
        <input type="text" class="form-control" name="code" id="code"
            value="{{ $state ? $state->code : '' }}" placeholder="e.g. KL" maxlength="5" required>
    </div>
</div>
