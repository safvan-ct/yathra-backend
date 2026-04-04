@if ($role)
    <input type="hidden" name="id" value="{{ $role->id }}">
@endif

<div class="row">
    <div class="col-md-6 mb-3">
        <label for="display_name" class="form-label fw-bold">Role Title</label>
        <input type="text" class="form-control" name="display_name" id="display_name"
            value="{{ $role ? $role->display_name : '' }}" placeholder="e.g. Fleet Manager" required>
        <small class="text-muted">Human readable name for the role.</small>
    </div>

    <div class="col-md-6 mb-3">
        <label for="name" class="form-label fw-bold">Role Key (Slug)</label>
        <input type="text" class="form-control" name="name" id="name" value="{{ $role ? $role->name : '' }}"
            placeholder="e.g. fleet-manager" required>
        <small class="text-muted">System identifier (unique, no spaces).</small>
    </div>

    <div class="col-md-12 mb-4">
        <label for="description" class="form-label fw-bold">Role Description</label>
        <textarea class="form-control" name="description" id="description" rows="2"
            placeholder="Describe the responsibilities of this role...">{{ $role ? $role->description : '' }}</textarea>
    </div>

    <div class="col-md-12 mb-3">
        <h6 class="fw-bold border-bottom pb-2 mb-3">
            <i class="ti ti-lock-access me-1 text-primary"></i>Permission Matrix
        </h6>

        <div class="row g-3">
            @foreach ($groupedPermissions as $module => $permissions)
                <div class="col-12">
                    <div class="matrix-module-card border p-3 rounded-3 bg-light-soft">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="fw-bold text-dark fs-6">{{ $module }} Capabilities</span>
                            <div class="form-check form-switch">
                                <input class="form-check-input select-all-module" type="checkbox"
                                    data-module="{{ Str::slug($module) }}" id="select_all_{{ Str::slug($module) }}"
                                    @if (count($permissions) == count($role->permissions)) checked @endif>
                                <label class="form-check-label small text-muted"
                                    for="select_all_{{ Str::slug($module) }}">Select All</label>
                            </div>
                        </div>

                        <div class="row row-cols-1 row-cols-md-3 row-cols-lg-4 g-2">
                            @foreach ($permissions as $perm)
                                <div class="col">
                                    <div class="form-check custom-matrix-check p-2 rounded-2 transition">
                                        <input class="form-check-input perm-check {{ Str::slug($module) }}-check"
                                            type="checkbox" name="permissions[]" value="{{ $perm->id }}"
                                            id="perm_{{ $perm->id }}"
                                            @if ($role && $role->permissions->contains($perm->id)) checked @endif>
                                        <label class="form-check-label fs-7 ms-1 cursor-pointer"
                                            for="perm_{{ $perm->id }}">
                                            {{ $perm->display_name }}
                                        </label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('.select-all-module').on('change', function() {
            const moduleClass = $(this).data('module');
            $(`.${moduleClass}-check`).prop('checked', $(this).is(':checked'));
        });

        // Highlight selected
        $('.perm-check').on('change', function() {
            if ($(this).is(':checked')) {
                $(this).closest('.custom-matrix-check').addClass(
                    'bg-light-primary border-light-primary');
            } else {
                $(this).closest('.custom-matrix-check').removeClass(
                    'bg-light-primary border-light-primary');
            }
        }).trigger('change');
    });
</script>
