@extends('layouts/contentNavbarLayout')

@section('title', 'Permission Table')

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
          <div class="mb-5 row">
            <!-- Select Tables with checkboxes inside a dropdown -->
            <div class="col-md-6 border-end">
              <label for="tableNames" class="form-label">Select Table</label>
              <div class="dropdown w-100">
                <button class="form-select" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                  Select Table
                </button>
                <ul class="dropdown-menu w-100" aria-labelledby="dropdownMenuButton">
                  @foreach($tableNames as $table)
                    <li class="mx-3">
                      <div class="form-check">
                        <input class="form-check-input table-checkbox" type="checkbox" value="{{ $table }}" id="table_{{ $table }}">
                        <label class="form-check-label" for="table_{{ $table }}">
                          {{ ucfirst(strtolower($table)) }}
                        </label>
                      </div>
                    </li>
                  @endforeach
                </ul>
              </div>
              <div id="selectedTablesContainer" class="d-flex flex-wrap mt-2 mb-2 gap-1"></div>
              <!-- Hidden inputs for selected tables -->
              <div id="hiddenTablesContainer"></div>
            </div>

            <!-- Select Permissions with checkboxes inside a dropdown -->
            <div class="col-md-6">
              <label for="permissionNames" class="form-label">Select Permission</label>
              <div class="dropdown w-100">
                <button class="form-select" type="button" id="permissionDropdownButton" data-bs-toggle="dropdown" aria-expanded="false">
                  Select Permission
                </button>
                <ul class="dropdown-menu w-100" aria-labelledby="permissionDropdownButton">
                  @foreach(['Create', 'View', 'Update', 'Delete', 'Import', 'Export'] as $perm)
                    <li class="mx-3">
                      <div class="form-check">
                        <input class="form-check-input permission-checkbox" type="checkbox" value="{{ $perm }}" id="permission_{{ strtolower($perm) }}" >
                        <label class="form-check-label" for="permission_{{ strtolower($perm) }}">{{ $perm }}</label>
                      </div>
                    </li>
                  @endforeach
                </ul>
              </div>
              <div id="selectedPermissionsContainer" class="d-flex flex-wrap mt-2 mb-2 gap-1"></div>
              <!-- Hidden inputs for selected permissions -->
              <div id="hiddenPermissionsContainer"></div>
            </div>
          </div>

          <hr>

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
  $(document).ready(function() {
    // Handle selected tables
    $('.table-checkbox').on('change', function() {
      let selectedTables = [];
      $('.table-checkbox:checked').each(function() {
        selectedTables.push($(this).val());
      });
      $('#selectedTablesContainer').html('');
      $('#hiddenTablesContainer').html('');
      selectedTables.forEach(function(table) {
        $('#selectedTablesContainer').append(`
          <span class="badge" style="background-color: 	#00308F;">
            ${table}
            <span class="ms-1 fw-bold remove-item" style="cursor:pointer;" data-type="table" data-value="${table}">×</span>
          </span>
        `);
        // Create hidden inputs for form submission
        $('#hiddenTablesContainer').append(`
          <input type="hidden" name="tables[]" value="${table}">
        `);
      });
    });

    // Handle selected permissions
    $('.permission-checkbox').on('change', function() {
      let selectedPermissions = [];
      $('.permission-checkbox:checked').each(function() {
        selectedPermissions.push($(this).val());
      });
      $('#selectedPermissionsContainer').html('');
      $('#hiddenPermissionsContainer').html('');
      selectedPermissions.forEach(function(permission) {
        $('#selectedPermissionsContainer').append(`
          <span class="badge" style="background-color: #01411C">
            ${permission}
            <span class="ms-1 fw-bold remove-item" style="cursor:pointer;" data-type="permission" data-value="${permission}">×</span>
          </span>
        `);
        // Create hidden inputs for form submission
        $('#hiddenPermissionsContainer').append(`
          <input type="hidden" name="permissions[]" value="${permission}">
        `);
      });
    });

    // Remove on x click
    $(document).on('click', '.remove-item', function () {
      let type = $(this).data('type');
      let value = $(this).data('value');
      if (type === 'table') {
        $(`.table-checkbox[value="${value}"]`).prop('checked', false).trigger('change');
      } else if (type === 'permission') {
        $(`.permission-checkbox[value="${value}"]`).prop('checked', false).trigger('change');
      }
    });
  });
</script>
@endsection
