@extends('layouts/contentNavbarLayout')

@section('title', ' Horizontal Layouts - Forms')

@section('content')
<div class="container d-flex justify-content-center">
  <div class="col-md-10">
    <div class="card mb-6">
      <div class="card-header d-flex align-items-center justify-content-between">
        <h5 class="mb-0">Add Permissions</h5> 
        <small class="text-muted float-end">
          <a href="{{ route('roles.index') }}">Back</a>
        </small>
      </div>
      <div class="card-body">
        <form action="{{ route('roles.store') }}" method="POST" id="addForm">
          @csrf
          <div class="mb-5">
            <label for="permissionto" class="form-label">Permission To</label>
            <select class="form-select" id="permissionto" name="permissionto" required>
                <option value="" disabled selected>Select Permission to...</option>
                @foreach($permissiontoOptions as $option)
                    <option value="{{ $option }}">{{ $option }}</option>
                @endforeach
                <option value="add-new">Add New</option>
            </select>
          
           <!-- Add New Permission To -->
              <div class="mb-3 mt-3" id="new-permissionto-container" style="display: none;">
              <label for="new-permissionto" class="form-label">Add New Permission To</label>
              <input type="text" class="form-control" id="new-permissionto" name="new_permissionto" placeholder="Enter new permission to">
              </div>
            </div>
            <hr>
          <!-- Permission Input Fields -->
          <div id="permissions-container">
            <div class="mb-3 permission-item">
              <label for="permission-0" class="form-label">Permission Name</label>
              <input type="text" class="form-control" id="permission-0" name="permissions[]" placeholder="Enter permission name" required>
            </div>
          </div>
           <!-- Add/Remove Buttons -->
            <div class="mt-4 text-end">
            <button type="button" id="add-permission" class="btn btn-icon btn-outline-primary hover:bg-primary text-primary" title="Add More">
                <i class="bx bx-plus"></i>
            </button>
            <button type="button" id="remove-permission" class="btn btn-icon btn-outline-danger hover:bg-danger text-danger" title="Remove Last">
                <i class="bx bx-minus"></i>
            </button>
          </div>
          <!-- Submit Button -->
          <div class="mt-4">
            <button type="submit" class="btn btn-primary me-3">Add Permissions</button>
            <a href="{{ route('roles.index') }}" class="btn btn-outline-secondary">Cancel</a>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const container = document.getElementById('permissions-container');
    const addButton = document.getElementById('add-permission');
    const removeButton = document.getElementById('remove-permission');

    let permissionCount = 1;

    // Add new input field
    addButton.addEventListener('click', function () {
        const newField = document.createElement('div');
        newField.classList.add('mb-3', 'permission-item');
        newField.innerHTML = `
            <label for="permission-${permissionCount}" class="form-label">Permission Name</label>
            <input type="text" class="form-control" id="permission-${permissionCount}" name="permissions[]" placeholder="Enter permission name" required>
        `;
        container.appendChild(newField);
        permissionCount++;
    });

    // Remove last input field
    removeButton.addEventListener('click', function () {
        const items = container.getElementsByClassName('permission-item');
        if (items.length > 1) {
            container.removeChild(items[items.length - 1]);
            permissionCount--;
        }
    });
});
</script>

<!-- Adding New Type of Permission -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    const permissiontoSelect = document.getElementById('permissionto');
    const newPermissiontoContainer = document.getElementById('new-permissionto-container');
    const newPermissiontoInput = document.getElementById('new-permissionto');

    permissiontoSelect.addEventListener('change', function () {
        if (this.value === 'add-new') {
            newPermissiontoContainer.style.display = 'block';
        } else {
            newPermissiontoContainer.style.display = 'none';
        }
    });

    newPermissiontoInput.addEventListener('blur', function () {
        const newValue = newPermissiontoInput.value.trim();
        if (newValue) {
            const newOption = document.createElement('option');
            newOption.value = newValue;
            newOption.text = newValue;
            newOption.selected = true;
            permissiontoSelect.appendChild(newOption);
        }
    });
});
</script>
@endsection
