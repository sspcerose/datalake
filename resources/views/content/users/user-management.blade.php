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
        <a class="btn btn-primary btn-sm d-flex align-items-center me-3" href="{{ route('user-register') }}">
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
          <form action="/import-users" method="POST" enctype="multipart/form-data">
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
        <form action="/import-users" method="POST" enctype="multipart/form-data">
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
          @else
            <td><span class="badge bg-label-danger me-1">Inactive</span></td>
          @endif

          @if (auth()->user()->hasPermission('View Users') || auth()->user()->hasPermission('Update Users') || auth()->user()->hasPermission('Delete Users'))
          <td>
           @if (auth()->user()->hasPermission('View Users'))
              <div class="d-flex align-items-center">
                <a href="{{ route('user.show', ['user' => $user->id]) }}" class="text-primary me-2" title="View">
                  <i class='bx bx-show'></i>
                </a>
            @endif
            @if (auth()->user()->hasPermission('Update Users'))
                <a href="{{ route('user.edit', ['user' => $user->id]) }}" class="text-warning me-2" title="Update">
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
                                        <a href="/user/${user.id}/show" class="text-primary me-2" title="View">
                                              <i class='bx bx-show'></i>
                                          </a>
                                              <!-- Update Icon -->
                                              <a href="/user/${user.id}/edit" class="text-warning me-2" title="Update">
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

@endsection
