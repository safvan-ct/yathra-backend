<input type="hidden" name="id" value="{{ $id ?? 0 }}">

<div class="row">
    <div class="col-md-2 mb-3">
        <label class="form-label">Type</label>
        <select name="type" class="form-select">
            <option value="Private" {{ isset($operator) && $operator->type == 'Private' ? 'selected' : '' }}>
                Private
            </option>
            <option value="Government" {{ isset($operator) && $operator->type == 'Government' ? 'selected' : '' }}>
                Government
            </option>
        </select>
    </div>
    <div class="col-md-4 mb-3">
        <label class="form-label">Operator Name</label>
        <input type="text" name="name" class="form-control" value="{{ $operator->name ?? '' }}" required>
    </div>
    <div class="col-md-3 mb-3">
        <label class="form-label">Phone</label>
        <input type="text" name="phone" class="form-control" value="{{ $operator->phone ?? '' }}" required>
    </div>
    <div class="col-md-3 mb-3">
        <label class="form-label">Email</label>
        <input type="email" name="email" class="form-control" value="{{ $operator->email ?? '' }}">
    </div>

    <div class="col-12 mb-3">
        <label class="form-label">Address</label>
        <textarea name="address" class="form-control" rows="2">{{ $operator->address ?? '' }}</textarea>
    </div>

    <div class="col-md-12 mb-3">
        <div class="form-check form-switch">
            <input class="form-check-input" type="checkbox" name="is_public" value="1" id="is_public"
                {{ isset($operator) && $operator->is_public ? 'checked' : '' }}>
            <label class="form-check-label" for="is_public">Public Listing</label>
        </div>
    </div>
</div>
