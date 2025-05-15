@extends('layouts/contentNavbarLayout')

@section('title', 'Weather Table')
@section('content')


<div class="card">
  <h5 class="card-header d-flex justify-content-between align-items-center">
    Weather

  </h5>
  <div class="table-responsive text-nowrap">
    <div class="container mb-3">
      <div class="row align-items-center">
        <!-- Left Section: Add, Import, Export buttons -->
        <div class="col-md-6 d-flex justify-content-start">
          @if (auth()->user()->hasPermission('Create Weather'))
            <a class="btn btn-primary btn-sm d-flex align-items-center me-3" href="{{ route('weather.create') }}">
              <i class="bx bx-plus-circle me-2"></i> Add
            </a>
          @endif
          @if (auth()->user()->hasPermission('Import Weather'))
            
            <!-- <button class="btn btn-sm d-flex align-items-center me-3 text-white" style="background-color: #539812;" id="importButton" data-bs-toggle="modal" data-bs-target="#modalCenter">
              <i class="bx bx-import me-2"></i> Import
            </button> -->
            <button class="btn btn-sm d-flex align-items-center me-3 text-white"
                  style="background-color: #539812;"
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
          @if (auth()->user()->hasPermission('Export Weather'))
            <a href="{{ route('export.weather') }}" class="btn btn-warning btn-sm d-flex align-items-center">
              <i class="bx bx-export me-2"></i> Export
            </a>
          @endif
        </div>
        <div class="col-md-6 d-flex justify-content-end">
          <!-- Search Input -->
          <input type="text" id="searchInput" placeholder="Search..." class="p-2 border border-dark rounded">
        </div>
      </div>
    </div>

<table class="table" id="allResultsTable">

<!-- Modal for Importing -->
<div class="modal fade" id="modalCenter" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalCenterTitle">IMPORT FILE</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <!-- <form action="{{ route('import-weather.process') }}" method="POST" enctype="multipart/form-data">
          @csrf -->
          <div class="mb-3">
            <label for="csvFileModal" class="form-label">Upload CSV File</label>
            <input type="file" id="csvFile" name="file" class="form-control" required accept=".csv, application/vnd.ms-excel, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet">
          </div>
          <!-- Progress Bar -->
          <div id="progressContainer" class="d-none mt-4">
            <label for="progressBar" class="form-label">Uploading</label>
            <div class="progress" style="height: 15px;">
              <div id="progressBar" class="progress-bar bg-primary"  role="progressbar"  style="" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
                <p id="progressText" class="text-center my-2">0%</p>
              </div>
            </div>
          </div>

          <!-- Import Progress Bar
          <div id="importProgressContainer" class="d-none mt-4">
            <label for="importProgressBar" class="form-label">Import Progress</label>
            <div class="Importprogress" style="height: 15px;">
              <div id="importProgressBar" class="progress-bar bg-success" role="importprogressbar"  style="" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
                <p id="importProgressText" class="text-center my-2">0%</p>
              </div>
            </div>
          </div> -->

          
          <!-- Message -->
          <div id="message" class="mt-4"></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline-secondary me-2" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn text-white" id="uploadButton" style="background-color: #539812;">Import</button>
      </div>
      <!-- </form> -->
       <!-- Progress -->
       <div id="uploadStatus" class="mt-3 p-3 border border-1 rounded-3"></div>
    </div>
  </div>
</div>

    <thead class="table-dark">
        <tr>
            <!-- <th>ID</th> -->
            <th style="width: 20%"><x-sortable-column field="city_mun_code" label="City/Municipality Code" route="weather.index" /></th>
            <!-- <th style="width: 20%"><x-sortable-column field="ave_min" label="Avg Min Temp (°C)" route="weather.index" /></th> -->
            <!-- <th style="width: 20%"><x-sortable-column field="ave_max" label="Avg Max Temp (°C)" route="weather.index" /></th> -->
            <th style="width: 20%"><x-sortable-column field="ave_mean" label="Avg Mean Temp (°C)" route="weather.index" /></th>
            <th style="width: 20%"><x-sortable-column field="rainfall_mm" label="Rainfall (mm)" route="weather.index" /></th>
            <!-- <th style="width: 20%"><x-sortable-column field="rainfall_description" label="Rainfall Description" route="weather.index" /></th> -->
            <th style="width: 20%"><x-sortable-column field="cloud_cover" label="Cloud Cover" route="weather.index" /></th>
            <!-- <th style="width: 20%"><x-sortable-column field="humidity" label="Humidity (%)" route="weather.index" /></th> -->
            <th style="width: 20%"><x-sortable-column field="forecast_date" label="Forecast Date" route="weather.index" /></th>
            <!-- <th style="width: 20%"><x-sortable-column field="date_accessed" label="Date Accessed" route="weather.index" /></th> -->
            <th style="width: 20%"><x-sortable-column field="wind_mps" label="Wind Speed (m/s)" route="weather.index" /></th>
            <!-- <th style="width: 20%"><x-sortable-column field="direction" label="Direction" route="weather.index" /></th> -->
            @if (auth()->user()->hasPermission('View Weather') || auth()->user()->hasPermission('Update Weather') || auth()->user()->hasPermission('Delete Weather'))
            <th style="width: 10%">Actions</th>
                    @endif
                </tr>
            </thead>
            @if($weatherData->isEmpty())
                <tbody>
                    <tr><td>Empty</td></tr>
                </tbody>
            @else
            <tbody>
                @foreach ($weatherData as $weather)
                    <tr>
                        <!-- <td>{{ $weather->id }}</td> -->
                        <td class="text-wrap">{{ $weather->city_mun_code ?? '-'}}</td>
                        <!-- <td class="text-wrap">{{ $weather->ave_min ?? '-' }}</td> -->
                        <!-- <td class="text-wrap">{{ $weather->ave_max ?? '-' }}</td> -->
                        <td class="text-wrap">{{ $weather->ave_mean ?? '-' }}</td>
                        <td class="text-wrap">{{ $weather->rainfall_mm ?? '-' }} %</td>
                        <!-- <td class="text-wrap">{{ $weather->rainfall_description ?? '-' }}</td> -->
                        <td class="text-wrap">{{ ucwords(strtolower($weather->cloud_cover)) ?? '-' }}</td>
                        <!-- <td class="text-wrap">{{ $weather->humidity ?? '-' }}</td> -->
                        <td class="text-wrap">{{ \Carbon\Carbon::parse($weather->forecast_date)->format('F j, Y') ?? '-' }}</td>
                        <!-- <td class="text-wrap">{{ $weather->date_accessed ?? '-' }}</td> -->
                        <td class="text-wrap">{{ $weather->wind_mps ?? '-' }}</td>
                        <!-- <td class="text-wrap">{{ $weather->direction ?? '-' }}</td> -->
                        @if (auth()->user()->hasPermission('View Weather') || auth()->user()->hasPermission('Update Weather') || auth()->user()->hasPermission('Delete Weather'))
                        <td>
                        <div class="d-flex align-items-center">
                        @if (auth()->user()->hasPermission('View Weather'))
                            <a href="{{ route('weather.show', ['weather' => $weather->id]) }}" class="text-primary me-2" title="View">
                                <i class='bx bx-show'></i>
                            </a>
                        @endif
                        @if (auth()->user()->hasPermission('Update Weather'))
                            <a href="{{ route('weather.edit', ['weather' => $weather->id]) }}" class="text-warning me-2" title="Update">
                                <i class="bx bx-edit-alt"></i>   
                            </a>
                        @endif
                        @if (auth()->user()->hasPermission('Delete Weather'))
                            <form action="{{ route('weather.destroy', [$weather->id]) }}" method="POST" id="deleteForm{{ $weather->id }}" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                  <a href="#" class="text-danger confirmDeleteButton" title="Delete" data-form-id="deleteForm{{ $weather->id }}">
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
            @endif
        </table>
        <div id="allpagination"class="mt-4">
      {{ $weatherData->links(('vendor.pagination.bootstrap-5')) }}
    </div>
</div>

<!-- <div id="importProgressContainer" class="toast-container position-fixed bottom-0 end-0 p-3 d-none" style="z-index: 1055;">
<button type="button" class="btn-close btn-close-white " data-bs-dismiss="modal" aria-label="Close"></button>
  <div class="toast show align-items-center border-1" style="border: 2px solid rgb(216, 222, 225); border-radius: 5px;  border-color: gray; box-shadow: 0px 0px 5px gray;">
        
    <div class="d-flex">
      <div class="toast-body" style="width: 100%;">
        <div class="progress" style="height: 15px;">
          <div id="importProgressBar" class="progress-bar bg-primary" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
            <span id="importProgressText" class="text-white">0%</span>
          </div>
        </div>
        New element for showing rows processed -->
        <!-- <div id="importProgressDetails" class="mt-2 text-muted small text-center">
          0 / 0
        </div>
      </div> -->
      <!-- <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button> -->
    <!-- </div>
  </div>
</div> -->

<div id="importProgressContainer" class="bs-toast toast toast-placement-ex m-4 fade bottom-0 end-0 hide" role="alert" aria-live="assertive" aria-atomic="true" 
          style="background-color:rgb(255, 255, 255); border: 1px solid rgb(193, 187, 187);  opacity:1;">

          <div class="toast-header">
            <i class='bx bx-bell me-2'></i>
            <div class="me-auto fw-medium">Inserting</div>
            <!-- <small>11 mins ago</small> -->
            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
          </div>
          <div class="toast-body">
          <div class="progress" style="height: 15px;">
          <div id="importProgressBar" class="progress-bar bg-primary" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
            <span id="importProgressText" class="text-white">0%</span>
          </div>
        </div>
        <!-- New element for showing rows processed -->
        <div id="importProgressDetails" class="mt-2 text-muted small text-center">
          0 / 0
        </div>
          </div>
        </div>

<!-- Search -->
<table class="table" id="resultsTable">
    <thead class="table-dark d-none">
        <tr>
            <!-- <th>ID</th> -->
            <th><x-sortable-column field="city_mun_code" label="City/Municipality Code" route="weather.index" /></th>
            <!-- <th><x-sortable-column field="ave_min" label="Avg Min Temp (°C)" route="weather.index" /></th> -->
            <!-- <th><x-sortable-column field="ave_max" label="Avg Max Temp (°C)" route="weather.index" /></th> -->
            <th><x-sortable-column field="ave_mean" label="Avg Mean Temp (°C)" route="weather.index" /></th>
            <th><x-sortable-column field="rainfall_mm" label="Rainfall (mm)" route="weather.index" /></th>
            <!-- <th><x-sortable-column field="rainfall_description" label="Rainfall Description" route="weather.index" /></th> -->
            <th><x-sortable-column field="cloud_cover" label="Cloud Cover (%)" route="weather.index" /></th>
            <!-- <th><x-sortable-column field="humidity" label="Humidity (%)" route="weather.index" /></th> -->
            <th><x-sortable-column field="forecast_date" label="Forecast Date" route="weather.index" /></th>
            <!-- <th><x-sortable-column field="date_accessed" label="Date Accessed" route="weather.index" /></th> -->
            <th><x-sortable-column field="wind_mps" label="Wind Speed (m/s)" route="weather.index" /></th>
            <!-- <th><x-sortable-column field="Direction" label="Direction" route="weather.index" /></th> -->
            @if (auth()->user()->hasPermission('View Weather') || auth()->user()->hasPermission('Update Weather') || auth()->user()->hasPermission('Delete Weather'))
            <th>Actions</th>
            @endif
        </tr>
    </thead>
      <tbody>
    </tbody>
</table>
<div id="paginationLinks"class="mt-4">
</div>
</div>
<hr>

<!-- Search -->
<script>
$(document).ready(function () {
    const allPaginationDiv = $('#allpagination'); 
    const paginationDiv = $('#paginationLinks'); 
    const tableBody = $('#resultsTable tbody');
    const allResultsTableBody = $('#allResultsTable');
    const searchInput = $('#searchInput'); 
    const tableHeader = $('#resultsTable thead'); 


    function performSearch(query, url = '/weather-search') {
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
                    data.data.forEach((weather) => {
                    let cloudCoverFormatted = weather.cloud_cover.charAt(0).toUpperCase() + weather.cloud_cover.slice(1).toLowerCase();
                    let forecastDateFormatted = new Date(weather.forecast_date).toLocaleDateString('en-US', {
                        year: 'numeric',
                        month: 'long',
                        day: 'numeric'
                    });

                    let actionColumn = '';
                            if (@json(Auth::user()->user_type) !== 'Viewer') {
                                actionColumn = `<td class="text-wrap">
                                <div class="d-flex align-items-center">
                                    @if (auth()->user()->hasPermission('View Weather'))
                                        <a href="/table1/${weather.id}/show" class="text-primary me-2" title="View">
                                            <i class='bx bx-show'></i>
                                        </a>
                                    @endif
                                    <!-- Update Icon -->
                                    @if (auth()->user()->hasPermission('Update Weather'))
                                    <a href="/table1/${weather.id}/edit" class="text-warning me-2" title="Update">
                                        <i class="bx bx-edit-alt"></i>
                                    </a>
                                    @endif
                                    <!-- Delete Icon -->
                                    @if (auth()->user()->hasPermission('Delete Weather'))
                                    <form action="/table1/${weather.id}" method="POST" id="deleteForm${weather.id}" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                            <a href="#" class="text-danger delete-btn" title="Delete"  data-form-id="deleteForm${weather.id}">
                                                <i class="bx bx-trash"></i>
                                            </a>
                                        </form>
                                    @endif
                                    </div>
                            </td>`;
                            }
                        const row = `
                            <tr>
                                <td class="text-wrap">${weather.city_mun_code}</td>
                                <td class="text-wrap">${weather.ave_mean }</td>
                                <td class="text-wrap">${weather.rainfall_mm }%</td>
                                <td class="text-wrap">${cloudCoverFormatted }</td>
                                <td class="text-wrap">${forecastDateFormatted }</td>
                                <td class="text-wrap">${weather.wind_mps }</td>
                                
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



<script>
    //Progress Bar 
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
    } else {
        console.error('Progress bar elements not found in DOM.');
    }
}

   $('#uploadButton').click(async function (e) {
    e.preventDefault();
    const fileInput = $('#csvFile')[0].files[0];

    if (!fileInput) {
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: 'Please select a file to upload!'
        });
        return;
    }

    const chunkSize = 5 * 1024 * 1024; // 5MB per chunk
    const uploadUrl = '{{ route('import-weather.process') }}';
    
    let uploadedChunks = 0;
    let start = 0;
    const reader = new FileReader();

    reader.onload = async function (event) {
        let fileText = event.target.result;
        let totalSize = fileText.length;

        while (start < totalSize) {
            // Find the last newline in the chunk range
            let end = Math.min(start + chunkSize, totalSize);
            let newlinePos = fileText.lastIndexOf("\n", end); // Find last newline in chunk

            // If no newline found within the chunk, take full chunk up to 'end'
            if (newlinePos === -1 || newlinePos <= start) {
                newlinePos = end; // Use full chunk if no newline found
            }

            let chunk = fileText.slice(start, newlinePos + 1); // Include the newline
            start = newlinePos + 1; // Move the start pointer to the next chunk start

            let formData = new FormData();
            formData.append('file', new Blob([chunk], { type: 'text/csv' }));
            formData.append('index', uploadedChunks);
            formData.append('totalChunks', Math.ceil(totalSize / chunkSize));
            formData.append('fileName', fileInput.name);

            try {
                // updateStatus(`Uploading chunk ${uploadedChunks + 1}...`, 'info');
                await $.ajax({
                    url: uploadUrl,
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: formData,
                    processData: false,
                    contentType: false,
                    beforeSend: function () {
                        console.log('Sending request to:', uploadUrl);
                        console.log('Uploading chunk index:', uploadedChunks);
                    },
                    success: function (response) {
                        uploadedChunks++;
                        let progress = Math.round((uploadedChunks / Math.ceil(totalSize / chunkSize)) * 100);
                        console.log('Upload Progress:', progress + '%');
                        // updateStatus(`Uploading... ${progress}%`, 'info');
                        updateStatus(progress);

                        if (uploadedChunks === Math.ceil(totalSize / chunkSize)) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Upload Complete',
                                text: 'File uploaded successfully!',
                                willOpen: () => {
                                    const swalContainer = document.querySelector('.swal2-container');
                                    if (swalContainer) {
                                        swalContainer.style.zIndex = '9999';
                                    }
                                }
                            });
                            location.reload();
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

    reader.readAsText(fileInput); // Read the file as text to preserve line breaks
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
            title: "Delete Weather Info?",
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
document.addEventListener('DOMContentLoaded', function () {
  const progressBar = document.getElementById('importProgressBar');
  const progressText = document.getElementById('importProgressText');
  const progressContainer = document.getElementById('importProgressContainer');
  const progressDetails = document.getElementById('importProgressDetails'); 

  const toast = new bootstrap.Toast(progressContainer); 

  const intervalId = setInterval(() => {
    fetch('/table-import-status') /
      .then(response => {
        if (response.status === 204) {
          console.log('Import is complete. Interval stopped.');
          // location.reload();
          progressDetails.textContent = "Completed";
          clearInterval(intervalId); 
          toast.hide(); 

        //   setTimeout(() => {
        //     toast.hide();
        //     progressDetails.textContent = "";
        // }, 30000); 

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

        console.log('Progress:', progress); // Debug log for progress value

        // Format numbers with commas
        const rowsProcessedFormatted = data.rows_processed.toLocaleString();
        const totalRowsFormatted = data.total_rows.toLocaleString();

        // Update progress bar width and text
        progressBar.style.width = `${progress}%`;
        progressBar.setAttribute('aria-valuenow', Math.round(progress));
        progressText.textContent = `${Math.round(progress)}%`;

        // Update the progress details
        progressDetails.textContent = `${rowsProcessedFormatted} / ${totalRowsFormatted}`;

        // Show the progress bar if hidden and progress has started
        if (progress > 0 && progressContainer.classList.contains('hide')) {
          toast.show(); // Show the toast
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

<!-- Notification -->
<script>
  document.addEventListener('DOMContentLoaded', () => {
    const notificationIcon = document.getElementById('notificationIcon');
    const notificationContainer = document.getElementById('notificationContainer');

    notificationIcon.addEventListener('click', () => {
      notificationContainer.classList.toggle('d-none');
    });

    // Close notification container when clicking outside
    document.addEventListener('click', (event) => {
      if (!notificationIcon.contains(event.target) && !notificationContainer.contains(event.target)) {
        notificationContainer.classList.add('d-none');
      }
    });
  });
</script>


<!-- THIRD PARTY [Pusher] (NOT WORKING) might be useful in the future-->
<!-- Real Time Update (In Progress) -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://js.pusher.com/7.2/pusher.min.js"></script>
<script>
    import Echo from 'laravel-echo';

    window.Pusher = require('pusher-js');

    window.Echo = new Echo({
        broadcaster: 'pusher',
        key: '{{ config("broadcasting.connections.pusher.key") }}',
        cluster: '{{ config("broadcasting.connections.pusher.options.cluster") }}',
        forceTLS: true,
    });

    window.Echo.channel('csv-import')
        .listen('.csv.import.success', (e) => {
            Swal.fire({
                title: 'Import Successful!',
                text: e.message,
                icon: 'success',
                confirmButtonText: 'OK'
            });
        });
</script>



@endsection