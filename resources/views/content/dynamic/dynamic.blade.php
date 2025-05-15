@extends('layouts/contentNavbarLayout')

@section('content')
<div class="card">
  <h5 class="card-header d-flex justify-content-between align-items-center">
    <strong>{{ ucfirst($selectedTable) }} Table</strong>
</h5>
    <!-- Add, import, export, and search -->
  <div class="table-responsive text-nowrap">
    <div class="container mb-3">
      <div class="row align-items-center">
        <div class="col-md-6 d-flex justify-content-start">
          @if (auth()->user()->hasPermission('Create '. $selectedTable))
            <a class="btn btn-primary btn-sm me-3" href="{{ route('table.create', $selectedTable) }}">
              <i class="bx bx-plus-circle me-2"></i> Add
            </a>
          @endif
          @if (auth()->user()->hasPermission('Import '. $selectedTable))
            <button class="btn btn-sm text-white me-3" style="background-color: #539812;" 
              id="importButton"
              data-bs-toggle="modal"
              data-bs-target="#modalCenter"
              @if(!empty($import_process) && ($import_process->status ?? null) === 'on going') 
                disabled 
              @endif>
              <i class="bx bx-import me-2"></i>
                 @if(!empty($import_process) && ($import_process->status ?? null) === 'on going') 
                    Importing 
                 @else 
                    Import 
                 @endif
            </button>
          @endif
          @if (auth()->user()->hasPermission('Export '. $selectedTable))
            <a href="{{ route('table.export', ['table' => $selectedTable]) }}" class="btn btn-warning btn-sm d-flex align-items-center">
              <i class="bx bx-export me-2"></i> Export
            </a>
          @endif
              </div>
              <div class="col-md-6 d-flex justify-content-end">
                <input type="text" id="searchInput" placeholder="Search..." class="p-2 border border-dark rounded">
              </div>
            </div>
          </div>

  <!-- Table -->
  <div class="table-responsive text-nowrap">
    <table class="table table-hover" id="resultsTable">
      <!-- Modal for Importing -->
      <div class="modal fade" id="modalCenter" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="modalCenterTitle">IMPORT FILE TO {{ strtoupper($selectedTable) }}</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <input type="hidden" id="selectedTable" value="{{ $selectedTable }}">
              <div class="mb-3">
                <label for="csvFile" class="form-label">Upload CSV File</label>
                <input type="file" id="csvFile" name="file" class="form-control" required accept=".csv, application/vnd.ms-excel, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet">
              </div>
              <!-- Progress Bar -->
              <div id="progressContainer" class="d-none mt-4">
                <label for="progressBar" class="form-label">Uploading the file</label>
                <div class="progress" style="height: 15px;">
                  <div id="progressBar" class="progress-bar bg-primary" role="progressbar" style="" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
                    <p id="progressText" class="text-center my-2">0%</p>
                  </div>
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
              <button type="button" class="btn btn-primary" id="uploadButton">Upload</button>
            </div>
          </div>
        </div>
      </div>
      <!-- End Modal -->

  <!-- Import Progress Toast -->
    <div id="importProgressContainer" class="bs-toast toast toast-placement-ex m-4 fade bottom-0 end-0 hide" role="alert" aria-live="assertive" aria-atomic="true" 
          style="background-color:rgb(255, 255, 255); border: 1px solid rgb(193, 187, 187); opacity:1;">
      <div class="toast-header">
        <i class='bx bx-bell me-2'></i>
        <div class="me-auto fw-medium">Inserting into {{ ucfirst($selectedTable) }}</div>
        <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
      </div>
      <div class="toast-body">
        <div class="progress" style="height: 15px;">
          <div id="importProgressBar" class="progress-bar bg-primary" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
            <span id="importProgressText" class="text-white">0%</span>
          </div>
        </div>
        <div id="importProgressDetails" class="mt-2 text-muted small text-center">
          0 / 0
        </div>
      </div>
    </div>
    <!-- End Toast -->
    <!-- Table Header -->
    <thead class="table-dark">
      <tr>
        @foreach($columns as $col)
          @if(!in_array($col, ['id', 'created_at', 'updated_at']))
            @php
              $currentSortField = request('sort_field');
              $currentSortOrder = request('sort_order');
              
              if($currentSortField === $col) {
                $nextSortOrder = $currentSortOrder === 'asc' ? 'desc' : ($currentSortOrder === 'desc' ? 'none' : 'asc');
                $sortIcon = $currentSortOrder === 'asc' ? '⥣' : ($currentSortOrder === 'desc' ? '⥥' : '⥮');
              } else {
                $nextSortOrder = 'asc';
                $sortIcon = '⥮'; 
              }
            @endphp
            <th style="width: 15%;">
              <a href="{{ route('table.viewer', ['table' => $selectedTable, 'sort_field' => $col, 'sort_order' => $nextSortOrder]) }}" class="text-reset text-decoration-none">
                {{ ucfirst (str_replace('_', ' ',$col ))}} &nbsp;&nbsp; {{ $sortIcon }} 
              </a>
            </th>
          @endif
        @endforeach

        @if (auth()->user()->hasPermission('View ' . $selectedTable) || auth()->user()->hasPermission('Update ' . $selectedTable) || auth()->user()->hasPermission('Delete ' . $selectedTable))
          <th style="width: 10%;">Actions</th>
        @endif
      </tr>
    </thead>
    <tbody id="resultsTableBody">
      @forelse($rows as $row)
        <tr id="row-{{ $row->id }}">
          @foreach($columns as $col)
            @if(!in_array($col, ['id', 'created_at', 'updated_at']))
              <td style="width: 150px;">{{ ucwords($row->$col) }}</td>
            @endif
          @endforeach

          @if (auth()->user()->hasPermission('View ' . $selectedTable) || auth()->user()->hasPermission('Update ' . $selectedTable) || auth()->user()->hasPermission('Delete ' . $selectedTable))
            <td>
              <div class="d-flex align-items-center">
                @if (auth()->user()->hasPermission('View ' . $selectedTable))
                  <a href="{{ route('table.view', [$selectedTable, $row->id]) }}" class="text-primary me-2"><i class="bx bx-show"></i></a>
                @endif
                @if (auth()->user()->hasPermission('Update ' . $selectedTable))
                <a href="{{ route('table.edit', [$selectedTable, $row->id]) }}" class="text-warning me-2"><i class="bx bx-edit-alt"></i></a>
                @endif
                @if (auth()->user()->hasPermission('Delete ' . $selectedTable))
                  <form method="POST" action="{{ route('table.delete', [$selectedTable, $row->id]) }}" id="deleteForm{{ $row->id }}">
                    @csrf
                    @method('DELETE')
                      <a href="#" class="text-danger confirmDeleteButton" data-form-id="deleteForm{{ $row->id }}" data-row-id="{{ $row->id }}"><i class="bx bx-trash"></i></a>
                  </form>
                @endif
              </div>
            </td>
          @endif
        </tr>
        @empty
          <tr><td colspan="{{ count($columns) }}">No data found in this table.</td></tr>
        @endforelse
    </tbody>
  </table>
<!-- Pagination -->
  <div class="mt-3" id="paginationLinks">
    {{ $rows->links('vendor.pagination.bootstrap-5') }}
  </div>
</div>
</div>
</div>
</div>  
</div>
</div>
</div>
<hr class="mt-12">

<!-- Success Alert -->
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

<!-- Search Scripts -->
<script>
  $(document).ready(function () {
    const searchInput = $('#searchInput');
    const resultsTableBody = $('#resultsTableBody');
    const paginationLinks = $('#paginationLinks');
    const selectedTable = "{{ $selectedTable }}";

    function fetchResults(query = '', page = 1) {
      $.ajax({
        url: "{{ url('/tables') }}/" + selectedTable,
        method: 'GET',
        data: { query: query, page: page },
        success: function (response) {
          resultsTableBody.empty();
          paginationLinks.empty();

          if (response.data.length === 0) {
            resultsTableBody.append(`<tr><td colspan="{{ count($columns) }}">No results found.</td></tr>`);
          } else {
            response.data.forEach(row => {
              let rowHtml = `<tr id="row-${row.id}">`;

              @foreach($columns as $col)
                @if(!in_array($col, ['id', 'created_at', 'updated_at']))
                  rowHtml += `<td style="width: 150px;">${row['{{ $col }}'] || ''}</td>`;
                @endif
              @endforeach

              rowHtml += `
                <td>
                  <div class="d-flex align-items-center">
                    @if (auth()->user()->hasPermission('View ' . $selectedTable))
                    <a href="/tables/${selectedTable}/view/${row.id}" class="text-primary me-2"><i class="bx bx-show"></i></a>
                    @endif
                    @if (auth()->user()->hasPermission('Update ' . $selectedTable))
                    <a href="/tables/${selectedTable}/edit/${row.id}" class="text-warning me-2"><i class="bx bx-edit-alt"></i></a>
                    @endif
                     @if (auth()->user()->hasPermission('Delete ' . $selectedTable))
                    <form method="POST" action="/tables/${selectedTable}/delete/${row.id}" id="deleteForm${row.id}">
                      <input type="hidden" name="_token" value="{{ csrf_token() }}">
                      <input type="hidden" name="_method" value="DELETE">
                      <a href="#" class="text-danger confirmDeleteButton" data-form-id="deleteForm${row.id}" data-row-id="${row.id}"><i class="bx bx-trash"></i></a>
                    </form>
                    @endif
                  </div>
                </td>
              </tr>`;
              resultsTableBody.append(rowHtml);
            });
          }

          paginationLinks.html(response.links);
        }
      });
    }

    // Live search
    searchInput.on('keyup', function () {
      const query = $(this).val();
      fetchResults(query);
    });

    // Pagination
    $(document).on('click', '#paginationLinks a', function (e) {
      e.preventDefault();
      const page = $(this).attr('href').split('page=')[1];
      fetchResults(searchInput.val(), page);
    });

    // Delete confirm (works for both normal and search table)
    $(document).on('click', '.confirmDeleteButton', function (e) {
      e.preventDefault();
      const formId = $(this).data('form-id');
      const rowId = $(this).data('row-id');
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
          $.ajax({
            url: form.attr('action'),
            method: 'POST',
            data: form.serialize(),
            success: function () {
              $('#row-' + rowId).remove();
              Swal.fire("Deleted!", "Your entry has been deleted.", "success");
            },
            error: function () {
              Swal.fire("Error!", "Something went wrong.", "error");
            }
          });
        }
      });
    });
  });
</script>

<!-- Upload File -->
<script>
  // Progress Bar
  function updateStatus(progress) {
      progress = Math.min(Math.max(progress, 0), 100);
      console.log('Progress:', progress);
      
      const progressBar = document.getElementById('progressBar');
      const progressText = document.getElementById('progressText');
      const progressContainer = document.getElementById('progressContainer');

      if (progressBar && progressText && progressContainer) {
          progressBar.style.width = `${progress}%`;
          progressBar.setAttribute('aria-valuenow', progress);
          progressText.textContent = `${Math.round(progress)}%`;

          if (progress > 0 && progressContainer.classList.contains('d-none')) {
              progressContainer.classList.remove('d-none');
          }
      }
  }

  $('#uploadButton').click(async function (e) {
      e.preventDefault();
      const fileInput = $('#csvFile')[0].files[0];
      const selectedTable = $('#selectedTable').val();
      const importRoute = "{{ route('table.import.process', ['table' => ':table']) }}".replace(':table', selectedTable);

      if (!fileInput) {
          Swal.fire({
              icon: 'error',
              title: 'Oops...',
              text: 'Please select a file to upload!'
          });
          return;
      }

      const chunkSize = 5 * 1024 * 1024; // 5MB per chunk
      
      let uploadedChunks = 0;
      let start = 0;
      const reader = new FileReader();

      reader.onload = async function (event) {
          let fileText = event.target.result;
          let totalSize = fileText.length;

          while (start < totalSize) {
              let end = Math.min(start + chunkSize, totalSize);
              let newlinePos = fileText.lastIndexOf("\n", end);

              if (newlinePos === -1 || newlinePos <= start) {
                  newlinePos = end;
              }

              let chunk = fileText.slice(start, newlinePos + 1);
              start = newlinePos + 1;

              let formData = new FormData();
              formData.append('file', new Blob([chunk], { type: 'text/csv' }));
              formData.append('index', uploadedChunks);
              formData.append('totalChunks', Math.ceil(totalSize / chunkSize));
              formData.append('fileName', fileInput.name);

              try {
                  await $.ajax({
                      url: importRoute,
                      type: 'POST',
                      headers: {
                          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                      },
                      data: formData,
                      processData: false,
                      contentType: false,
                      beforeSend: function () {
                          console.log('Sending request to:', importRoute);
                          console.log('Uploading chunk index:', uploadedChunks);
                      },
                      success: function (response) {
                          uploadedChunks++;
                          let progress = Math.round((uploadedChunks / Math.ceil(totalSize / chunkSize)) * 100);
                          console.log('Upload Progress:', progress + '%');
                          updateStatus(progress);

                          if (uploadedChunks === Math.ceil(totalSize / chunkSize)) {
                              Swal.fire({
                                  icon: 'success',
                                  title: 'Upload Complete',
                                  text: 'File uploaded successfully! Processing will continue in the background.',
                                  willOpen: () => {
                                      const swalContainer = document.querySelector('.swal2-container');
                                      if (swalContainer) {
                                          swalContainer.style.zIndex = '9999';
                                      }
                                  }
                              });
                              $('#modalCenter').modal('hide');
                          }
                      },
                      error: function (error) {
                          Swal.fire({
                              icon: 'error',
                              title: 'Upload Failed',
                              text: 'An error occurred while uploading.'
                          });
                      }
                  });
              } catch (error) {
                  console.error('Upload error:', error);
              }
          }
      };

      reader.readAsText(fileInput);
  });

  // Import progress polling
  document.addEventListener('DOMContentLoaded', function () {
    const progressBar = document.getElementById('importProgressBar');
    const progressText = document.getElementById('importProgressText');
    const progressContainer = document.getElementById('importProgressContainer');
    const progressDetails = document.getElementById('importProgressDetails');
    const toast = new bootstrap.Toast(progressContainer);
    const selectedTable = $('#selectedTable').val();
    const statusRoute = "{{ route('table.import.status', ['table' => ':table']) }}".replace(':table', selectedTable);

    const intervalId = setInterval(() => {
      fetch(statusRoute)
        .then(response => {
          if (response.status === 204) {
            console.log('Import is complete. Interval stopped.');
            progressDetails.textContent = "Completed";
            clearInterval(intervalId);
            toast.hide();
            setTimeout(() => {
              toast.hide();
              // location.reload();
            }, 2000);
            return null;
          } else if (response.ok) {
            return response.json();
          } else {
            throw new Error(`Unexpected response: ${response.status}`);
          }
        })
        .then(data => {
          if (!data) return;

          const progress = (data.rows_processed / data.total_rows) * 100;
          console.log('Progress:', progress);

          const rowsProcessedFormatted = data.rows_processed.toLocaleString();
          const totalRowsFormatted = data.total_rows.toLocaleString();

          progressBar.style.width = `${progress}%`;
          progressBar.setAttribute('aria-valuenow', Math.round(progress));
          progressText.textContent = `${Math.round(progress)}%`;
          progressDetails.textContent = `${rowsProcessedFormatted} / ${totalRowsFormatted}`;

          if (progress > 0 && progressContainer.classList.contains('hide')) {
            toast.show();
            progressContainer.classList.remove('hide');
          }
        })
        .catch(error => {
          console.error('Error fetching progress:', error);
          clearInterval(intervalId);
        });
    }, 1000);
  });
</script>
@endsection
