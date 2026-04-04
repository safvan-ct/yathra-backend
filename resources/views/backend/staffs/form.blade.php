@if ($staff)
    <input type="hidden" name="id" value="{{ $staff->id }}">
@endif

<div class="row">
    <div class="col-md-6 mb-3">
        <label for="name" class="form-label fw-bold">Full Name</label>
        <input type="text" class="form-control" name="name" id="name" value="{{ $staff ? $staff->name : '' }}"
            placeholder="e.g. John Doe" required>
    </div>

    <div class="col-md-6 mb-3">
        <label for="email" class="form-label fw-bold">Email Address</label>
        <input type="email" class="form-control" name="email" id="email"
            value="{{ $staff ? $staff->email : '' }}" placeholder="john@yathra.com" required>
        <small class="text-muted">Will be used for login.</small>
    </div>

    <div class="col-md-6 mb-3">
        <label for="password" class="form-label fw-bold">Password</label>
        <input type="password" class="form-control" name="password" id="password" placeholder="••••••••"
            @if (!$staff) required @endif>
        <small class="text-muted">
            @if ($staff)
                Leave blank to keep current password.
            @else
                Min. 8 characters.
            @endif
        </small>
    </div>

    <div class="col-md-6 mb-3">
        <label for="roles" class="form-label fw-bold">Assigned Roles</label>
        <select class="form-select text-capitalize choices-select" name="roles[]" id="roles" multiple required>
            @foreach ($allRoles as $role)
                <option value="{{ $role->id }}" @if ($staff && $staff->roles->contains($role->id)) selected @endif>
                    {{ $role->display_name }}
                </option>
            @endforeach
        </select>
        <small class="text-muted">Search and select one or more roles.</small>
    </div>
</div>

<script>
    if (typeof Choices !== 'undefined') {
        const element = document.querySelector('.choices-select');
        new Choices(element, {
            removeItemButton: true,
            placeholderValue: 'Select roles...',
            searchPlaceholderValue: 'Search roles...',
        });
    }
</script>
