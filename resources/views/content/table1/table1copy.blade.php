@extends('layouts/contentNavbarLayout')

<!-- @section('title', 'Dashboard - Analytics') -->
@section('content')
@section('title', 'Tables - Basic Tables')

<!-- Basic Bootstrap Table -->
<div class="card">
  <h5 class="card-header">Table 1</h5>  
  <div class="table-responsive text-nowrap">
  <div class="container mb-3">
    <div class="row align-items-center">
    <!-- Left Section: Add, Import, Export buttons -->
    <div class="col-md-6 d-flex justify-content-start">
    @if(Auth::user()->user_type != 'User Type 1' && Auth::user()->user_type != 'Viewer' )
        <a class="btn btn-primary btn-sm d-flex align-items-center me-3" href="{{ route('table1.create') }}">
            <i class="bx bx-plus-circle me-2"></i> Add
        </a>
        <button class="btn btn-sm d-flex align-items-center me-3 text-white" style="background-color: #539812;" id="importButton" data-bs-toggle="modal" data-bs-target="#modalCenter">
            <i class="bx bx-import me-2"></i> Import
        </button>
        <!-- <button class="btn btn-info btn-sm d-flex align-items-center me-3 text-white" id="importButton" data-bs-toggle="modal" data-bs-target="#modalCenter">
            <i class="bx bx-import me-2"></i> Import
        </button> -->
        <a href="{{ route('export.csv') }}" class="btn btn-warning btn-sm d-flex align-items-center">
            <i class="bx bx-export me-2"></i> Export
        </a>
     @endif
    </div>
    <!-- Progress Bar -->
    <!-- <div id="progressContainer" class="d-none mt-4">
        <label for="progressBar" class="form-label">Import Progress</label>
        <div class="progress">
            <div id="progressBar" class="progress-bar progress-bar-striped bg-success" role="progressbar" style="width: 0%;">0%</div>
        </div>
    </div>
</form> -->
        <!-- Right Section: Sort, Filter, Search -->
    <div class="col-md-6 d-flex justify-content-end">
        
            <!-- Search Input -->
            <input type="text" id="searchInput" placeholder="Search..." class="p-2 border rounded">
        </div>
    </div>
</div>

<table class="table" id="allResultsTable">

<!-- Modal for Importing -->
<div class="modal fade" id="modalCenter" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalCenterTitle">IMPORT CSV</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <!-- <form action="{{ route('import.process') }}" method="POST" enctype="multipart/form-data">
          @csrf -->
          <div class="mb-3">
            <label for="csvFileModal" class="form-label">Upload CSV File</label>
            <input type="file" id="csvFile" name="file" class="form-control" required>
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
        <button type="button" class="btn btn-success" id="uploadButton">Import</button>
      </div>
      <!-- </form> -->
    </div>
  </div>
</div>

<!-- Table Header -->
    <thead class="table-dark">
        <tr>
            <th style="width: 20%"><x-sortable-column field="track_name" label="Track" route="dashboard-analytics" /></th>
            <th style="width: 15%"><x-sortable-column field="artist_name" label="Artist" route="dashboard-analytics" /></th>
            <th style="width: 25%"><x-sortable-column field="album_name" label="Album" route="dashboard-analytics" /></th>
            <th style="width: 10%"><x-sortable-column field="reason_start" label="Reason Start" route="dashboard-analytics" /></th>
            <th style="width: 10%"><x-sortable-column field="reason_end" label="Reason End" route="dashboard-analytics" /></th>
            <th style="width: 10%"><x-sortable-column field="shuffle" label="Shuffle" route="dashboard-analytics" /></th>
            <th style="width: 10%"><x-sortable-column field="skipped" label="Skipped" route="dashboard-analytics" /></th>
            @if (auth()->user()->hasPermission('Update Histories') || auth()->user()->hasPermission('Delete Histories'))
            <th style="width: 10%">Actions</th>
            @endif
        </tr>
    </thead>
    @if($samples->isEmpty())
      <tbody>
        <tr>
            <td>No samples found.</td>
        </tr>
      </tbody>
    @else
      @foreach($samples as $sample)
      <tbody class="table-border-bottom-0">
        <tr>
            <!-- <td>{{ $sample->track_uri }}</td> -->
            <!-- <td>{{ $sample->t_time }}</td> -->
            <!-- <td>{{ $sample->platform }}</td> -->
            <!-- <td>{{ $sample->ms_played }}</td> -->
            <td class="text-wrap">{{ $sample->track_name }}</td>
            <td class="text-wrap">{{ $sample->artist_name }}</td>
            <td class="text-wrap">{{ $sample->album_name}}</td>
            <td class="text-wrap">{{ $sample->reason_start }}</td>
            <td class="text-wrap">{{ $sample->reason_end }}</td>
            <td class="text-wrap">{{ $sample->shuffle }}</td>
            <td class="text-wrap">{{ $sample->skipped }}</td>
            
            @if (auth()->user()->hasPermission('Update Table 1') || auth()->user()->hasPermission('Delete Table 1'))
                <td>
                    <div class="d-flex align-items-center">
                        @if (auth()->user()->hasPermission('Update Table 1'))
                            <a href="{{ route('table1.edit', ['table1' => $sample->id]) }}" class="text-warning me-2" title="Update">
                                <i class="bx bx-edit-alt"></i>
                            </a>
                        @endif

                        @if (auth()->user()->hasPermission('Delete Table 1'))
                            <form action="{{ route('table1.destroy', [$sample->id]) }}" method="POST" id="deleteForm{{ $sample->id }}" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <a href="#" class="text-danger confirmDeleteButton" title="Delete" data-form-id="deleteForm{{ $sample->id }}">
                                    <i class="bx bx-trash"></i>
                                </a>
                            </form>
                        @endif
                    </div>
                </td>
            @endif
        </tr>
        @endforeach
      </tbody>
    </table>
    @endif
  <div id="allpagination"class="mt-4">
      {{ $samples->links(('vendor.pagination.bootstrap-5')) }}
  </div>

<!-- Search -->
<table class="table" id="resultsTable">
      <thead class="table-dark d-none">
        <tr>
        <th style="width: 20%;">Track
        <button type="submit" name="sort_column" value="track_asc" class="btn btn-link p-0 text-white">
        <i class="bx bxs-up-arrow bx-xs"></i>
          </button>
          <button type="submit" name="sort_column" value="track_desc" class="btn btn-link p-0 text-white">
              <i class="bx bx-down-arrow bx-xs"></i>
          </button>
        </th>
        <th style="width: 15%;">Artist</th>
        <th style="width: 30%;">Album</th>
        <th style="width: 10%;">Reason Start</th>
        <th style="width: 10%;">Reason End</th>
        <th style="width: 5%;">Shuffle</th>
        <th style="width: 5%;">Skipped</th>
        @if(Auth::user()->user_type != 'User Type 1' && Auth::user()->user_type != 'Viewer')
        <th style="width: 5%;">Actions</th>
        @endif
        </tr>
      </thead>
      <tbody>
      </tbody>
      </table>
      <div id="paginationLinks"class="mt-4"></div>
  </div>
</div>
<hr>


<!-- Search -->
<script>
$(document).ready(function () {
    console.log(window.config);
    const allPaginationDiv = $('#allpagination'); 
    const paginationDiv = $('#paginationLinks'); 
    const tableBody = $('#resultsTable tbody');
    const allResultsTableBody = $('#allResultsTable');
    const searchInput = $('#searchInput'); 
    const tableHeader = $('#resultsTable thead'); 

    function performSearch(query, url = '/search') {
        // for debugging
        console.log('Performing search...');
        console.log('Search query:', query);
        console.log('Search URL:', url);

        $.ajax({
            url: url,
            method: 'GET',
            data: { query: query },
            success: function (data) {
                 // for debugging
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
                    data.data.forEach((sample) => {
                        let actionColumn = '';
                            if (@json(Auth::user()->user_type) !== 'User Type 1' && @json(Auth::user()->user_type) !== 'Viewer') {
                                actionColumn = `
                                    <td class="text-wrap">
                                        <div class="d-flex align-items-center">
                                            <!-- Update Icon -->
                                            <a href="/table1/${sample.id}/edit" class="text-warning me-2" title="Update">
                                                <i class="bx bx-edit-alt"></i>
                                            </a>
                                            <!-- Delete Icon -->
                                            <form action="/table1/${sample.id}" method="POST" id="deleteForm${sample.id}" style="display: inline;">
                                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                <input type="hidden" name="_method" value="DELETE">
                                                <a href="#" class="text-danger delete-btn" title="Delete" data-form-id="deleteForm${sample.id}">
                                                    <i class="bx bx-trash"></i>
                                                </a>
                                            </form>
                                        </div>
                                    </td>`;
                            }
                        const row = `
                            <tr>
                                <td class="text-wrap">${sample.track_name}</td>
                                <td class="text-wrap">${sample.artist_name}</td>
                                <td class="text-wrap">${sample.album_name}</td>
                                <td class="text-wrap">${sample.reason_start}</td>
                                <td class="text-wrap">${sample.reason_end}</td>
                                <td class="text-wrap">${sample.shuffle}</td>
                                <td class="text-wrap">${sample.skipped}</td>
                                ${actionColumn}
                            </tr>`;
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

<!-- File Upload -->
<script>
    $('#uploadButton').click(function (e) {
    e.preventDefault();
    const fileInput = $('#csvFile')[0].files[0];

    if (!fileInput) {
        $('.modal').css('z-index', '1040'); 
        $('.modal-backdrop').css('z-index', '1035');
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: 'Please select a file to upload!',
            didClose: () => {
                $('.modal').css('z-index', '1055');
                $('.modal-backdrop').css('z-index', '1040');
            }
        });
    }

    let formData = new FormData();
    formData.append('file', fileInput);

    console.log(fileInput);
    
    $('#progressContainer').removeClass('d-none');
    $('#progressBar').val(0);
    $('#progressText').text('0%');

    // checkProgress1();
    $.ajax({
        url: '{{ route('import.process') }}',
        type: 'POST',
        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
        data: formData,
        processData: false,
        contentType: false,
        success: function (data) {
            
            if (data.success) {
                $('#modalCenter').modal('hide');
                Swal.fire({
                    icon: 'success',
                    title: 'Upload Successful',
                    text: data.message || 'Your file has been uploaded successfully.',
                });
                location.reload();
            } else {
                // $('#message').text('Upload failed: ' + data.message).addClass('text-red-500');
                Swal.fire({
                    icon: 'error',
                    title: 'Upload Failed',
                    text: data.message || 'There was a problem with your upload.',
                });
            }
        },
        error: function () {
            $('#message').text('An error occurred during upload.').addClass('text-red-500');
            console.log(error);
        }
    });

    
    function checkProgress() {
        let interval = setInterval(function () {
        $.get('{{ route('progress') }}', function (data) {
            $('#progressBar').val(data.progress);
            $('#progressText').text(data.progress + '%');
            console.log('Import Progress:', data.progress + '%');

            if (data.progress >= 100) {
                clearInterval(interval);
                $('#message').text('File uploaded successfully!').addClass('text-green-500');
                setTimeout(() => location.reload(), 1000); 
            }
        });
    }, 1000); 
    }

    function checkProgress1() {
        let progress = 0;
        let interval = setInterval(function () {
        progress += 5; 

        $('#progressBar').css('width', progress + '%').attr('aria-valuenow', progress);
        $('#progressText').text(progress + '%');
        console.log('Import Progress:', progress + '%');

        if (progress >= 100) {
            clearInterval(interval);
        }
    }, 1500);
  }
});
</script>

<!-- Alert Nessage -->
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

@endsection
