@extends('layouts/contentNavbarLayout')

@section('content')
@section('title', 'Users Table')


<!-- Basic Bootstrap Table -->
<div class="card ">
  <h5 class="card-header">User List</h5>  
  <div class="table-responsive text-nowrap">
    <div class="container mb-3">
      <div class="row align-items-center">
        <div class="col-md-6 d-flex justify-content-start">
        @if (auth()->user()->hasPermission('Create Users'))
        <!-- <a class="btn btn-primary btn-sm d-flex align-items-center me-3" href="{{ route('user-register') }}">
            <i class="bx bx-plus-circle me-2"></i> Add
        </a> -->
        <a class="btn btn-primary btn-sm me-3 text-white" data-bs-toggle="modal" data-bs-target="#addUserModal">
          <i class="bx bx-plus-circle me-2"></i> Add
        </a>
        @endif
        @if (auth()->user()->hasPermission('Import Users'))
        <button class="btn btn-info btn-sm d-flex align-items-center me-3" id="importButton" data-bs-toggle="modal" data-bs-target="#modalCenter">
            <i class="bx bx-import me-2"></i> Import
        </button>
        @endif
        @if (auth()->user()->hasPermission('Export Users'))
        <a href="{{ route('export.users1') }}" class="btn btn-warning btn-sm d-flex align-items-center">
            <i class="bx bx-export me-2"></i> Export
        </a>
        @endif
        </div>
         <!-- Right Section: Sort, Filter, Search -->
         <div class="col-md-6 d-flex justify-content-end">
            <!-- Search Input -->
            <input type="text" id="searchInput" placeholder="Search..." class="p-2 border rounded">
        </div>
    </div>
</div>

<!-- Add User Modal -->
<div class="modal fade" id="addUserModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog" role="document" style="max-width: 600px;">
    <div class="modal-content border-0 shadow-lg">
      <div class="modal-header border-0 pb-0">
        <div>
          <h4 class="mb-0 text-primary fw-bold">Add New User</h4>
          <p class="mb-0 text-muted small">Fill in all required fields</p>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="addUserForm" action="{{ route('user-register') }}" method="POST">
          @csrf
          <div class="row g-3" id="addUserFields">
            <!-- Fields will be loaded via AJAX -->
          </div>
          <div class="modal-footer border-top-0 pt-4">
            <button type="button" class="btn btn-outline-secondary px-4" data-bs-dismiss="modal">
              <i class="bx bx-x me-1"></i> Cancel
            </button>
            <button type="submit" class="btn btn-primary px-4 shadow-sm">
              <i class="bx bx-plus me-1"></i> Add User
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- View User Modal -->
<div class="modal fade" id="viewUserModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog" role="document" style="max-width: 600px;">
    <div class="modal-content border-0 shadow-lg">
      <div class="modal-header border-0 pb-0">
        <div>
          <h4 class="mb-0 text-primary fw-bold">User Details</h4>
          <p class="mb-0 text-muted small">View user information</p>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="row g-3" id="viewUserFields">
          <!-- Fields will be loaded via AJAX -->
        </div>
        <div class="modal-footer border-top-0 pt-4">
          <button type="button" class="btn btn-outline-secondary px-4" data-bs-dismiss="modal">
            <i class="bx bx-x me-1"></i> Close
          </button>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Edit User Modal -->
<div class="modal fade" id="editUserModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog" role="document" style="max-width: 600px;">
    <div class="modal-content border-0 shadow-lg">
      <div class="modal-header border-0 pb-0">
        <div>
          <h4 class="mb-0 text-primary fw-bold">Edit User</h4>
          <p class="mb-0 text-muted small">Update user information</p>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="editUserForm" method="POST">
          @csrf
          @method('PUT')
          <div class="row g-3" id="editUserFields">
            <!-- Fields will be loaded via AJAX -->
          </div>
          <div class="modal-footer border-top-0 pt-4">
            <button type="button" class="btn btn-outline-secondary px-4" data-bs-dismiss="modal">
              <i class="bx bx-x me-1"></i> Cancel
            </button>
            <button type="submit" class="btn btn-primary px-4 shadow-sm">
              <i class="bx bx-save me-1"></i> Save Changes
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

@if($users->isEmpty())
  <table class="table" id="allResultsTable">

  <!-- Modal for Importing -->
  <div class="modal fade" id="modalCenter" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modalCenterTitle">IMPORT</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form action="{{route('import.process')}}" method="POST" enctype="multipart/form-data">
              @csrf
            <div class="mb-3">
              <label for="csvFileModal" class="form-label">Upload File</label>
              <input type="file" name="file" id="csvFile" class="form-control" required>
            </div>
            <!-- Progress Bar -->
            <div id="progressContainer" class="d-none mt-4">
              <label for="progressBar" class="form-label">Import Progress</label>
              <div class="progress">
                <div id="progressBar" class="progress-bar" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
                  <p id="progressText" class="text-center mt-2">0%</p>
                </div>
              </div>
            </div>
            <!-- Message -->
            <div id="message" class="mt-4"></div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-success" id="uploadButton">Import</button>
          </form>
        </div>
      </div>
    </div>
  </div>

      <thead class="table-dark">
        <tr> 
          <th><x-sortable-column field="first_name" label="Name" /></th>
          <th><x-sortable-column field="user_type" label="User Type" /></th>
          <th><x-sortable-column field="email" label="Email" /></th>
          <th><x-sortable-column field="status" label="Status" /></th>
          @if (auth()->user()->hasPermission('View Users') || auth()->user()->hasPermission('Update Users') || auth()->user()->hasPermission('Delete Users'))
          <th>Actions</th>
          @endif
        </tr>
      </thead>
      <tbody class="table-border-bottom-0">
        <tr>
          <td>No users found.</td>
        </tr>
      </tbody>
    </table>
    @else
    <table class="table" id="allResultsTable">
<!-- Modal for Importing -->
<div class="modal fade" id="modalCenter" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalCenterTitle">IMPORT</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action="{{route('import.users')}}" method="POST" enctype="multipart/form-data">
            @csrf
          <div class="mb-3">
            <label for="csvFileModal" class="form-label">Upload File</label>
            <input type="file" name="file" id="csvFile" class="form-control" required>
          </div>
          <!-- Progress Bar -->
          <div id="progressContainer" class="d-none mt-4">
            <label for="progressBar" class="form-label">Import Progress</label>
            <div class="progress">
              <div id="progressBar" class="progress-bar" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
                <p id="progressText" class="text-center mt-2">0%</p>
              </div>
            </div>
          </div>
          <!-- Message -->
          <div id="message" class="mt-4"></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-success" id="uploadButton">Import</button>
        </form>
      </div>
    </div>
  </div>
</div>

      <thead class="table-dark">
        <tr>
          <th><x-sortable-column field="first_name" label="First Name"  route="user-management" /></th>
          <th><x-sortable-column field="last_name" label="Last Name"  route="user-management" /></th>
          <th><x-sortable-column field="user_type" label="User Type"  route="user-management" /></th>
          <th><x-sortable-column field="email" label="Email"  route="user-management" /></th>
          <th><x-sortable-column field="status" label="Status"  route="user-management" /></th>
          @if (auth()->user()->hasPermission('View Users') || auth()->user()->hasPermission('Update Users') || auth()->user()->hasPermission('Delete Users'))
          <th>Actions</th>
          @endif
        </tr>
      </thead>
      @foreach($users as $user)
      <tbody class="table-border-bottom-0">
        <tr>
          <td>{{ $user->first_name }}</td>
          <td>{{ $user->last_name }}</td>
          <td>{{ $user->user_type }}</td>
          <td>{{ $user->email }}</td>
          @if($user->status == 'active')
            <td><span class="badge bg-label-primary me-1">Active</span></td>
          @elseif($user->status == 'inactive')
            <td><span class="badge bg-label-danger me-1">Inactive</span></td>
          @endif

          @if (auth()->user()->hasPermission('View Users') || auth()->user()->hasPermission('Update Users') || auth()->user()->hasPermission('Delete Users'))
          <td>
           @if (auth()->user()->hasPermission('View Users'))
              <div class="d-flex align-items-center">
                <!-- <a href="{{ route('user.show', ['user' => $user->id]) }}" class="text-primary me-2" title="View">
                  <i class='bx bx-show'></i>
                </a> -->
                <a href="#" class="text-primary me-2 view-btn" 
                  data-view-url="{{ route('user.show', ['user' => $user->id]) }}">
                  <i class='bx bx-show'></i>
                </a>
            @endif
            @if (auth()->user()->hasPermission('Update Users'))
                <!-- <a href="{{ route('user.edit', ['user' => $user->id]) }}" class="text-warning me-2" title="Update">
                    <i class="bx bx-edit-alt"></i>
                </a> -->
                <a href="#" class="text-warning me-2 edit-btn" 
                  data-edit-url="{{ route('user.edit', ['user' => $user->id]) }}"
                  data-update-url="{{ route('user.update', [$user->id]) }}">
                  <i class="bx bx-edit-alt"></i>
                </a>
            @endif
            @if (auth()->user()->hasPermission('Delete Users'))
                <form action="{{ route('user.destroy', [$user->id]) }}" method="POST" id="deleteForm{{ $user->id }}" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <a href="#" class="text-danger confirmDeleteButton" title="Delete" data-form-id="deleteForm{{ $user->id }}">
                        <i class="bx bx-trash"></i>
                    </a>
            @endif
                </form>
              </div>
            </div>
          </td>
          @endif
        </tr>
      </tbody>
      @endforeach
    </table>
    @endif
    <div id="allpagination"class="mt-4">
      {{ $users->links(('vendor.pagination.bootstrap-5')) }}
  </div>
  </div>
</div>
<!--/ Bootstrap Table with Header Dark -->
<table class="table" id="resultsTable">
</div>
<!-- Search -->
      <thead class="table-dark d-none">
        <tr>
        <th style="width: 15%;">First Name</th>
        <th style="width: 15%;">Last Name</th>
        <th style="width: 30%;">User Type</th>
        <th style="width: 10%;">Email</th>
        <th style="width: 10%;">Status</th>
        @if(Auth::user()->user_type != 'Admin' && Auth::user()->user_type != 'Viewer')
        <th style="width: 5%;">Actions</th>
        @endif
        </tr>
      </thead>
      <tbody>
      </tbody>
      </table>
      <div id="paginationLinks"class="mt-4"></div>
<hr>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Search -->
<script>
$(document).ready(function () {
    const allPaginationDiv = $('#allpagination'); 
    const paginationDiv = $('#paginationLinks'); 
    const tableBody = $('#resultsTable tbody');
    const allResultsTableBody = $('#allResultsTable');
    const searchInput = $('#searchInput'); 
    const tableHeader = $('#resultsTable thead'); 


    function performSearch(query, url = '/user-search') {
        console.log('Performing search...');
        console.log('Search query:', query);
        console.log('Search URL:', url);

        $.ajax({
            url: url,
            method: 'GET',
            data: { query: query },
            success: function (data) {
                console.log('AJAX request successful.');
                console.log('Received data:', data);

                tableBody.empty();
                paginationDiv.empty();

                if (query.trim() === '') {
                    allPaginationDiv.show();
                    paginationDiv.hide();
                    location.reload();
                    return;
                }

                allResultsTableBody.hide();
                allPaginationDiv.hide();
                paginationDiv.show();
                tableHeader.removeClass('d-none');

                if (data.data.length === 0) {
                    tableBody.append('<tr><td colspan="12">No results found.</td></tr>');
                } else {
                    data.data.forEach((user) => {
                      let actionColumn = '';
                            if (@json(Auth::user()->user_type) !== 'Admin' && @json(Auth::user()->user_type) !== 'Viewer') {
                                actionColumn = `
                                <td>
                                <div class="d-flex align-items-center">
                                  <a href="#" class="text-primary me-2 view-btn" 
                                      data-view-url="{{ route('user.show', ['user' => $user->id]) }}">
                                      <i class='bx bx-show'></i>
                                    </a>
                                        
                                              <!-- Update Icon -->
                                  <a href="#" class="text-warning me-2 edit-btn" 
                                      data-edit-url="{{ route('user.edit', ['user' => $user->id]) }}"
                                      data-update-url="{{ route('user.update', [$user->id]) }}">
                                      <i class="bx bx-edit-alt"></i>
                                  </a>

                                              <!-- Delete Icon -->
                                              <form action="/user/${user.id}" method="POST" id="deleteForm${user.id}" style="display: inline;">
                                                  @csrf
                                                  @method('DELETE')
                                                  <a href="#" class="text-danger delete-btn" title="Delete"  data-form-id="deleteForm${user.id}">
                                                      <i class="bx bx-trash"></i>
                                                  </a>
                                              </form>
                                       </div>
                              </td>`;
                            }
                        const row = `
                            <tr>
                                <td>${user.first_name}</td>
                                <td>${user.last_name}</td>
                                <td>${user.user_type}</td>
                                <td>${user.email}</td>
                                <td>
                                  <span class="badge 
                                      ${user.status === 'active' ? 'bg-label-primary' : 'bg-label-danger'} me-1">
                                      ${user.status === 'active' ? 'Active' : 'Inactive'}
                                  </span>
                              </td>
                              ${actionColumn}
                              
                            </tr>
                        `;
                        tableBody.append(row);
                    });

                    paginationDiv.html(data.links);
                }
            }
        });
    }

// Alert Message
$(document).ready(function () {

$(document).on('click', '.delete-btn', function (e) {
    e.preventDefault(); 

    const formId = $(this).data('form-id');
    const form = $('#' + formId); 

    Swal.fire({
        title: "Are you sure?",
        text: "You won't be able to revert this!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, delete it!"
    }).then((result) => {
        if (result.isConfirmed) {
            form.submit();
        }
    });
});
});

  // search on keyup
    searchInput.on('keyup', function () {
        const query = $(this).val();
        console.log('Search input changed:', query);
        performSearch(query);
    });

    // Pagination link
    $(document).on('click', '#paginationLinks a', function (e) {
        e.preventDefault();
        const url = $(this).attr('href');
        const query = searchInput.val();
        console.log('Pagination link clicked. URL:', url, 'Current query:', query);
        if (url) {
            performSearch(query, url);
        }
    });
  });
</script>

<!-- Alert Message -->
@if(session('success'))
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Success!',
            text: "{{ session('success') }}", 
            showConfirmButton: true
        });
    </script>
@endif

<!-- Delete -->
<script>
 $(document).ready(function () {
    $(document).on('click', '.confirmDeleteButton', function (e) {
        e.preventDefault(); 

        const formId = $(this).data('form-id');
        const form = $('#' + formId);
  
        Swal.fire({
            title: "Delete User?",
            text: "You won't be able to revert this!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, delete it!"
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();  
            }
        });
    });
});
</script>

<script>
  $(document).ready(function() {
    // Initialize Add User Modal
    $('#addUserModal').on('show.bs.modal', function() {
        $('#addUserFields').html(`
         <div class="col-12">
    <!-- Username -->
    <div class="p-3 border rounded bg-white mb-3">
        <div class="row align-items-center">
            <div class="col-md-4">
                <div class="d-flex align-items-center h-100">
                    <i class="bx bx-info-circle text-primary me-2"></i>
                    <h6 class="mb-0 text-primary fw-semibold text-uppercase small">Username</h6>
                </div>
            </div>
            <div class="col-md-8">
                <input type="text" class="form-control border-0 shadow-none" 
                       name="username" placeholder="Enter username" style="background-color: #E5E4E2;" required>
            </div>
        </div>
    </div>

    <!-- First Name -->
    <div class="p-3 border rounded bg-white mb-3">
        <div class="row align-items-center">
            <div class="col-md-4">
                <div class="d-flex align-items-center h-100">
                    <i class="bx bx-info-circle text-primary me-2"></i>
                    <h6 class="mb-0 text-primary fw-semibold text-uppercase small">First Name</h6>
                </div>
            </div>
            <div class="col-md-8">
                <input type="text" class="form-control border-0 shadow-none" 
                       name="first_name" placeholder="Enter first name" style="background-color: #E5E4E2;" required>
            </div>
        </div>
    </div>

    <!-- Last Name -->
    <div class="p-3 border rounded bg-white mb-3">
        <div class="row align-items-center">
            <div class="col-md-4">
                <div class="d-flex align-items-center h-100">
                    <i class="bx bx-info-circle text-primary me-2"></i>
                    <h6 class="mb-0 text-primary fw-semibold text-uppercase small">Last Name</h6>
                </div>
            </div>
            <div class="col-md-8">
                <input type="text" class="form-control border-0 shadow-none" 
                       name="last_name" placeholder="Enter last name" style="background-color: #E5E4E2;" required>
            </div>
        </div>
    </div>

    <!-- Email -->
    <div class="p-3 border rounded bg-white mb-3">
        <div class="row align-items-center">
            <div class="col-md-4">
                <div class="d-flex align-items-center h-100">
                    <i class="bx bx-info-circle text-primary me-2"></i>
                    <h6 class="mb-0 text-primary fw-semibold text-uppercase small">Email</h6>
                </div>
            </div>
            <div class="col-md-8">
                <input type="email" class="form-control border-0 shadow-none" 
                       name="email" placeholder="Enter email" style="background-color: #E5E4E2;" required>
            </div>
        </div>
    </div>

    <!-- User Type Dropdown -->
    <div class="p-3 border rounded bg-white mb-3">
        <div class="row align-items-center">
            <div class="col-md-4">
                <div class="d-flex align-items-center h-100">
                    <i class="bx bx-info-circle text-primary me-2"></i>
                    <h6 class="mb-0 text-primary fw-semibold text-uppercase small">User Type</h6>
                </div>
            </div>
            <div class="col-md-8">
                <select class="form-select shadow-none border-0" 
                        name="user_type" style="background-color: #E5E4E2;" required>
                    <option value="" disabled selected>Select user type</option>
                    @if (Auth::user()->user_type == 'Super Admin')
                      <option value="Super Admin">Super Admin</option>
                    @endif
                    <option value="Admin">Admin</option>
                    <option value="Viewer">Viewer</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Password -->
    <div class="p-3 border rounded bg-white mb-3">
        <div class="row align-items-center">
            <div class="col-md-4">
                <div class="d-flex align-items-center h-100">
                    <i class="bx bx-info-circle text-primary me-2"></i>
                    <h6 class="mb-0 text-primary fw-semibold text-uppercase small">Password</h6>
                </div>
            </div>
            <div class="col-md-8">
                <input type="password" class="form-control border-0 shadow-none" 
                       name="password" placeholder="Enter password" style="background-color: #E5E4E2;" required>
            </div>
        </div>
    </div>

    <!-- Confirm Password -->
    <div class="p-3 border rounded bg-white mb-3">
        <div class="row align-items-center">
            <div class="col-md-4">
                <div class="d-flex align-items-center h-100">
                    <i class="bx bx-info-circle text-primary me-2"></i>
                    <h6 class="mb-0 text-primary fw-semibold text-uppercase small">Confirm Password</h6>
                </div>
            </div>
            <div class="col-md-8">
                <input type="password" class="form-control border-0 shadow-none" 
                       name="confirmpassword" placeholder="Confirm password" style="background-color: #E5E4E2;" required>
            </div>
        </div>
    </div>
</div>


        `);
    });

    // Handle View Button
    $(document).on('click', '.view-btn', function(e) {
        e.preventDefault();
        const viewUrl = $(this).data('view-url');
        
        $('#viewUserFields').html(`
            <div class="col-12 text-center py-4">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>
        `);
        
        $('#viewUserModal').modal('show');
        
        $.get(viewUrl, function(response) {
            let fieldsHtml = `
                <div class="col-12">

    <!-- Username -->
    <div class="p-3 border rounded bg-white mb-3">
        <div class="row align-items-center">
            <div class="col-md-4">
                <div class="d-flex align-items-center h-100">
                    <i class="bx bx-user text-primary me-2"></i>
                    <h6 class="mb-0 text-primary fw-semibold text-uppercase small">Username</h6>
                </div>
            </div>
            <div class="col-md-8">
                <div class="form-control-plaintext" 
                     style="background-color: #E5E4E2; padding: 8px 12px; border-radius: 4px;">
                    ${response.username}
                </div>
            </div>
        </div>
    </div>

    <!-- First Name -->
    <div class="p-3 border rounded bg-white mb-3">
        <div class="row align-items-center">
            <div class="col-md-4">
                <div class="d-flex align-items-center h-100">
                    <i class="bx bx-user text-primary me-2"></i>
                    <h6 class="mb-0 text-primary fw-semibold text-uppercase small">First Name</h6>
                </div>
            </div>
            <div class="col-md-8">
                <div class="form-control-plaintext" 
                     style="background-color: #E5E4E2; padding: 8px 12px; border-radius: 4px;">
                    ${response.first_name}
                </div>
            </div>
        </div>
    </div>

    <!-- Last Name -->
    <div class="p-3 border rounded bg-white mb-3">
        <div class="row align-items-center">
            <div class="col-md-4">
                <div class="d-flex align-items-center h-100">
                    <i class="bx bx-user text-primary me-2"></i>
                    <h6 class="mb-0 text-primary fw-semibold text-uppercase small">Last Name</h6>
                </div>
            </div>
            <div class="col-md-8">
                <div class="form-control-plaintext" 
                     style="background-color: #E5E4E2; padding: 8px 12px; border-radius: 4px;">
                    ${response.last_name}
                </div>
            </div>
        </div>
    </div>

    <!-- Email -->
    <div class="p-3 border rounded bg-white mb-3">
        <div class="row align-items-center">
            <div class="col-md-4">
                <div class="d-flex align-items-center h-100">
                    <i class="bx bx-envelope text-primary me-2"></i>
                    <h6 class="mb-0 text-primary fw-semibold text-uppercase small">Email</h6>
                </div>
            </div>
            <div class="col-md-8">
                <div class="form-control-plaintext" 
                     style="background-color: #E5E4E2; padding: 8px 12px; border-radius: 4px;">
                    ${response.email}
                </div>
            </div>
        </div>
    </div>

    <!-- User Type -->
    <div class="p-3 border rounded bg-white mb-3">
        <div class="row align-items-center">
            <div class="col-md-4">
                <div class="d-flex align-items-center h-100">
                    <i class="bx bx-user-circle text-primary me-2"></i>
                    <h6 class="mb-0 text-primary fw-semibold text-uppercase small">User Type</h6>
                </div>
            </div>
            <div class="col-md-8">
                <div class="form-control-plaintext" 
                     style="background-color: #E5E4E2; padding: 8px 12px; border-radius: 4px;">
                    ${response.user_type}
                </div>
            </div>
        </div>
    </div>

    <div class="p-3 border rounded bg-white mb-3">
        <div class="row align-items-center">
            <div class="col-md-4">
                <div class="d-flex align-items-center h-100">
                    <i class="bx bx-check-circle text-primary me-2"></i>
                    <h6 class="mb-0 text-primary fw-semibold text-uppercase small">User Status</h6>
                </div>
            </div>
            <div class="col-md-8">
                <div class="form-control-plaintext" 
                     style="background-color: #E5E4E2; padding: 8px 12px; border-radius: 4px;">
                    ${response.status}
                </div>
            </div>
        </div>
    </div>

    <!-- Additional Fields -->
    <!-- Add more fields as required, following the same structure -->
</div>
`;
            
            $('#viewUserFields').html(fieldsHtml);
        });
    });

    // Handle Edit Button
    $(document).on('click', '.edit-btn', function(e) {
        e.preventDefault();
        const editUrl = $(this).data('edit-url');
        const updateUrl = $(this).data('update-url');
        
        $('#editUserFields').html(`
            <div class="col-12 text-center py-4">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>
        `);
        
        $('#editUserForm').attr('action', updateUrl);
        $('#editUserModal').modal('show');
        
        $.get(editUrl, function(response) {
            let fieldsHtml = `
               <div class="col-12">

    <!-- Username (Read-only) -->
    <div class="p-3 border rounded bg-white mb-3 transition-all hover-shadow">
        <div class="row align-items-center">
            <div class="col-md-4">
                <div class="d-flex align-items-center h-100">
                    <i class="bx bx-user text-primary me-2"></i>
                    <h6 class="mb-0 text-primary fw-semibold text-uppercase small">Username</h6>
                </div>
            </div>
            <div class="col-md-8">
                <input type="text" class="form-control border-0 shadow-none" 
                       name="username" value="${response.username}" 
                       style="background-color: #E5E4E2;" readonly>
            </div>
        </div>
    </div>

    <!-- First Name (Read-only) -->
    <div class="p-3 border rounded bg-white mb-3 transition-all hover-shadow">
        <div class="row align-items-center">
            <div class="col-md-4">
                <div class="d-flex align-items-center h-100">
                    <i class="bx bx-user text-primary me-2"></i>
                    <h6 class="mb-0 text-primary fw-semibold text-uppercase small">First Name</h6>
                </div>
            </div>
            <div class="col-md-8">
                <input type="text" class="form-control border-0 shadow-none" 
                       name="first_name" value="${response.first_name}" 
                       style="background-color: #E5E4E2;" readonly>
            </div>
        </div>
    </div>

    <!-- Last Name (Read-only) -->
    <div class="p-3 border rounded bg-white mb-3 transition-all hover-shadow">
        <div class="row align-items-center">
            <div class="col-md-4">
                <div class="d-flex align-items-center h-100">
                    <i class="bx bx-user text-primary me-2"></i>
                    <h6 class="mb-0 text-primary fw-semibold text-uppercase small">Last Name</h6>
                </div>
            </div>
            <div class="col-md-8">
                <input type="text" class="form-control border-0 shadow-none" 
                       name="last_name" value="${response.last_name}" 
                       style="background-color: #E5E4E2;" readonly>
            </div>
        </div>
    </div>

    <!-- Email (Read-only) -->
    <div class="p-3 border rounded bg-white mb-3 transition-all hover-shadow">
        <div class="row align-items-center">
            <div class="col-md-4">
                <div class="d-flex align-items-center h-100">
                    <i class="bx bx-envelope text-primary me-2"></i>
                    <h6 class="mb-0 text-primary fw-semibold text-uppercase small">Email</h6>
                </div>
            </div>
            <div class="col-md-8">
                <input type="email" class="form-control border-0 shadow-none" 
                       name="email" value="${response.email}" 
                       style="background-color: #E5E4E2;" readonly>
            </div>
        </div>
    </div>

    <!-- User Type (Editable Dropdown) -->
    <div class="p-3 border rounded bg-white mb-3 transition-all hover-shadow">
        <div class="row align-items-center">
            <div class="col-md-4">
                <div class="d-flex align-items-center h-100">
                    <i class="bx bx-user-circle text-primary me-2"></i>
                    <h6 class="mb-0 text-primary fw-semibold text-uppercase small">User Type</h6>
                </div>
            </div>
            <div class="col-md-8">
                <select class="form-select border-0 shadow-none" name="user_type" style="background-color: #E5E4E2;">
                    <option value="Super Admin" ${response.user_type === "Super Admin" ? "selected" : ""}>Super Admin</option>
                    <option value="Admin" ${response.user_type === "Admin" ? "selected" : ""}>Admin</option>
                    <option value="Viewer" ${response.user_type === "Viewer" ? "selected" : ""}>Viewer</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Status (Editable Dropdown) -->
    <div class="p-3 border rounded bg-white mb-3 transition-all hover-shadow">
        <div class="row align-items-center">
            <div class="col-md-4">
                <div class="d-flex align-items-center h-100">
                    <i class="bx bx-check-circle text-primary me-2"></i>
                    <h6 class="mb-0 text-primary fw-semibold text-uppercase small">Status</h6>
                </div>
            </div>
            <div class="col-md-8">
                <select class="form-select border-0 shadow-none" name="status" style="background-color: #E5E4E2;">
                    <option value="active" ${response.status === "active" ? "selected" : ""}>Active</option>
                    <option value="inactive" ${response.status === "inactive" ? "selected" : ""}>Inactive</option>
                </select>
            </div>
        </div>
    </div>

</div>
`;
            
            $('#editUserFields').html(fieldsHtml);
        });
    });

    // Form Submissions
    $('#addUserForm, #editUserForm').on('submit', function(e) {
        e.preventDefault();
        const form = $(this);
        
        $.ajax({
            url: form.attr('action'),
            method: form.attr('method'),
            data: form.serialize(),
            success: function(response) {
                // Close modal and show success message
                form.closest('.modal').modal('hide');
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: response.message || 'Operation completed successfully',
                    showConfirmButton: true
                }).then(() => {
                    location.reload();
                });
            },
            error: function(xhr) {
                if (xhr.status === 422) {
                    // Handle validation errors
                    const errors = xhr.responseJSON.errors;
                    let errorHtml = '';
                    
                    for (const field in errors) {
                        // Find the input field and its container
                        const inputField = document.querySelector(`[name="${field}"]`);
                        if (inputField) {
                            // Remove any existing error messages to avoid duplicates
                            const existingError = inputField.nextElementSibling;
                            if (existingError && existingError.classList.contains('text-danger')) {
                                existingError.remove();
                            }

                            // Append the error message after the input field
                            const errorElement = document.createElement('span');
                            errorElement.className = 'text-danger small fw-bold d-block mt-1';
                            errorElement.innerHTML = ` ${errors[field][0]}`;
                            inputField.parentNode.insertBefore(errorElement, inputField.nextSibling);
                        }
                    }
                    
                    form.find('.row.g-3').prepend(errorHtml);
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: xhr.responseJSON.message || 'An error occurred'
                    });
                }
            }
        });
    });
});
</script>

@endsection
