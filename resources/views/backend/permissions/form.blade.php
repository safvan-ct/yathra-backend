@if ($permission)
    <input type="hidden" name="id" value="{{ $permission->id }}">
@endif

<div class="row">
    <div class="col-md-12 mb-3">
        <label for="name" class="form-label fw-bold">Permission Name (Slug)</label>
        <input type="text" class="form-control" name="name" id="name"
            value="{{ $permission ? $permission->name : '' }}" placeholder="e.g. create-bus" required>
        <small class="text-muted">Unique identifier for the permission (no spaces only hyphen).</small>
    </div>

    <div class="col-md-12 mb-3">
        <label for="display_name" class="form-label fw-bold">Display Name</label>
        <input type="text" class="form-control" name="display_name" id="display_name"
            value="{{ $permission ? $permission->display_name : '' }}" placeholder="e.g. Create Bus" required>
        <small class="text-muted">Readable name for users.</small>
    </div>

    <div class="col-md-12 mb-3">
        <label for="description" class="form-label fw-bold">Description</label>
        <textarea class="form-control" name="description" id="description" rows="3"
            placeholder="Description of what this permission allows...">{{ $permission ? $permission->description : '' }}</textarea>
    </div>
</div>
