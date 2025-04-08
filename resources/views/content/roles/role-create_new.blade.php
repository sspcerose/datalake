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
            <!-- Select Tables  -->
            <label for="tableNames" class="form-label">Select Table</label>
            <div id="selectedTablesContainer" class="mb-2 d-flex flex-wrap gap-1"></div> <!-- Display selected tables -->
            <label for="tableNames" class="form-label">Select Table</label>
            <select class="form-select select2" id="tableNames" name="permissionto[]" multiple required>
                @foreach($tableNames as $table)
                    <option value="{{ $table }}">{{ $table }}</option>
                @endforeach
            </select>

            <!-- Select Permissions  -->
            <label for="permissionNames" class="form-label mt-2">Select Permission</label>
            <div id="selectedPermissionsContainer" class="mb-2 d-flex flex-wrap gap-1"></div> <!-- Display Selected Permissions -->
            <select class="form-select" id="permissionSelect" name="name[]" multiple>
                <option value="" disabled>Permissions</option>
                <option value="Create">Create</option>
                <option value="View">View</option>
                <option value="Update">Update</option>
                <option value="Delete">Delete</option>
                <option value="Import">Import</option>
                <option value="Export">Export</option>
            </select>

            
            <!-- end ng kay jashlie -->
          
           <!-- Add New Permission To -->
            <!-- <div class="mb-3 mt-3" id="new-permissionto-container" style="display: none;">
              <label for="new-permissionto" class="form-label">Add New Permission To</label>
              <input type="text" class="form-control" id="new-permissionto" name="new_permissionto" placeholder="Enter new permission to">
              </div>
            </div> -->
            <hr>
          <!-- Permission Input Fields -->
          <!-- <div id="permissions-container">
            <div class="mb-3 permission-item">
              <label for="permission-0" class="form-label">Add New Permission (Optional) </label>
              <input type="text" class="form-control" id="permission-0" name="permissions[]" placeholder="Enter permission name" required>
            </div>
          </div> -->
           <!-- Add/Remove Buttons -->
            <!-- <div class="mt-4 text-end">
            <button type="button" id="add-permission" class="btn btn-icon btn-outline-primary hover:bg-primary text-primary" title="Add More">
                <i class="bx bx-plus"></i>
            </button>
            <button type="button" id="remove-permission" class="btn btn-icon btn-outline-danger hover:bg-danger text-danger" title="Remove Last">
                <i class="bx bx-minus"></i>
            </button>
          </div> -->
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
    const permissiontoSelect = document.getElementById('tableNames');
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

<!-- For multiple select table -->
<script>
 document.addEventListener("DOMContentLoaded", function () {
    const tableSelect = document.getElementById("tableNames");
    const tableContainer = document.getElementById("selectedTablesContainer");

    tableSelect.addEventListener("change", function () {
        tableContainer.innerHTML = ''; // Clear previous selections
        Array.from(tableSelect.selectedOptions).forEach(option => {
            const item = document.createElement("span");
            item.textContent = option.text;
            item.classList.add("selected-item");

            const removeBtn = document.createElement("button");
            removeBtn.textContent = "×";
            removeBtn.classList.add("remove-btn");

            removeBtn.onclick = function () {
                option.selected = false;
                updateSelectedTables();
            };

            item.appendChild(removeBtn);
            tableContainer.appendChild(item);
        });
    });
});

</script>

<!-- select permission  -->
<script>
  document.addEventListener("DOMContentLoaded", function () {
    const permissionSelect = document.getElementById("permissionSelect");
    const permissionContainer = document.getElementById("selectedPermissionsContainer");

    permissionSelect.addEventListener("change", function () {
        permissionContainer.innerHTML = ''; // Clear previous selections
        Array.from(permissionSelect.selectedOptions).forEach(option => {
            const item = document.createElement("span");
            item.textContent = option.text;
            item.classList.add("selected-item");

            const removeBtn = document.createElement("button");
            removeBtn.textContent = "×";
            removeBtn.classList.add("remove-btn");

            removeBtn.onclick = function () {
                option.selected = false;
                updateSelectedPermissions();
            };

            item.appendChild(removeBtn);
            permissionContainer.appendChild(item);
        });
    });
});
</script>

<!-- select table style  -->
<style>
.selected-item {
    display: inline-flex;
    align-items: center;
    background: #f3f4f6; /* Light gray (Tailwind's gray-200) */
    color: #374151; /* Dark gray text */
    padding: 6px 12px;
    margin: 3px;
    border-radius: 6px;
    border: 1px solid #d1d5db; /* Subtle border (Tailwind's gray-300) */
    font-size: 14px;
    font-weight: 500;
}
.selected-item .remove-btn {
    background: none;
    border: none;
    color: #6b7280; /* Muted gray (Tailwind's gray-500) */
    margin-left: 8px;
    cursor: pointer;
    font-weight: bold;
    font-size: 14px;
}
.selected-item .remove-btn:hover {
    color: #ef4444; /* Red on hover (Tailwind's red-500) */
}
</style>

@endsection
