@extends('layouts/contentNavbarLayout')

@section('content')


<div class="card">
  <h5 class="card-header">Weather</h5>  
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
        <button class="btn btn-sm d-flex align-items-center me-3 text-white" style="background-color: #539812; id="importButton" data-bs-toggle="modal" data-bs-target="#modalCenter">
            <i class="bx bx-import me-2"></i> Import
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
        <!-- <form action="{{ route('import-weather.process') }}" method="POST" enctype="multipart/form-data">
          @csrf -->
          <div class="mb-3">
            <label for="csvFileModal" class="form-label">Upload CSV File</label>
            <input type="file" id="csvFile" name="file" class="form-control" required accept=".csv, application/vnd.ms-excel, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet">
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
            <!-- <th style="width: 20%"><x-sortable-column field="rainfall_mm" label="Rainfall (mm)" route="weather.index" /></th> -->
            <!-- <th style="width: 20%"><x-sortable-column field="rainfall_description" label="Rainfall Description" route="weather.index" /></th> -->
            <th style="width: 20%"><x-sortable-column field="cloud_cover" label="Cloud Cover" route="weather.index" /></th>
            <!-- <th style="width: 20%"><x-sortable-column field="humidity" label="Humidity (%)" route="weather.index" /></th> -->
            <th style="width: 20%"><x-sortable-column field="forecast_date" label="Forecast Date" route="weather.index" /></th>
            <!-- <th style="width: 20%"><x-sortable-column field="date_accessed" label="Date Accessed" route="weather.index" /></th> -->
            <th style="width: 20%"><x-sortable-column field="wind_mps" label="Wind Speed (m/s)" route="weather.index" /></th>
            <th style="width: 20%"><x-sortable-column field="Direction" label="Direction" route="weather.index" /></th>
                    @if(Auth::user()->user_type != 'User Type 1' && Auth::user()->user_type != 'Viewer' )
            <th style="width: 10%">Actions</th>
                    @endif
                </tr>
            </thead>
            @if($weatherData->isEmpty())
                <tbody>
                    <tr><td>no data</td></tr>
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
                        <!-- <td class="text-wrap">{{ $weather->rainfall_mm ?? '-' }} %</td> -->
                        <!-- <td class="text-wrap">{{ $weather->rainfall_description ?? '-' }}</td> -->
                        <td class="text-wrap">{{ ucwords(strtolower($weather->cloud_cover)) ?? '-' }}</td>
                        <!-- <td class="text-wrap">{{ $weather->humidity ?? '-' }}</td> -->
                        <td class="text-wrap">{{ \Carbon\Carbon::parse($weather->forecast_date)->format('F j, Y') ?? '-' }}</td>
                        <!-- <td class="text-wrap">{{ $weather->date_accessed ?? '-' }}</td> -->
                        <td class="text-wrap">{{ $weather->wind_mps ?? '-' }}</td>
                        <td class="text-wrap">{{ $weather->direction ?? '-' }}</td>
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
            <th><x-sortable-column field="Direction" label="Direction" route="weather.index" /></th>
            @if(Auth::user()->user_type != 'User Type 1' && Auth::user()->user_type != 'Viewer' )
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
                                    <a href="/table1/${weather.id}/show" class="text-primary me-2" title="View">
                                        <i class='bx bx-show'></i>
                                    </a>
                                    <!-- Update Icon -->
                                    <a href="/table1/${weather.id}/edit" class="text-warning me-2" title="Update">
                                        <i class="bx bx-edit-alt"></i>
                                    </a>
                                    <!-- Delete Icon -->
                                    <form action="/table1/${weather.id}" method="POST" id="deleteForm${weather.id}" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                            <a href="#" class="text-danger delete-btn" title="Delete"  data-form-id="deleteForm${weather.id}">
                                                <i class="bx bx-trash"></i>
                                            </a>
                                        </form>
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
                                <td class="text-wrap">${weather.direction }</td>
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
    function updateStatus(message, type = 'info') {
    const statusDiv = $('#uploadStatus');
    let color = type === 'error' ? 'red' : type === 'success' ? 'green' : 'blue';
    statusDiv.html(`<p style="color:${color};">${message}</p>`);
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
                        updateStatus(`Uploading... ${progress}%`, 'info');

                        if (uploadedChunks === Math.ceil(totalSize / chunkSize)) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Upload Complete',
                                text: 'File uploaded successfully. Processing and inserting data...',
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


<!-- File Upload -->
<script>
//     $('#uploadButton').click(function (e) {
//     e.preventDefault();
//     const fileInput = $('#csvFile')[0].files[0];

//     if (!fileInput) {
//         $('.modal').css('z-index', '1040'); 
//         $('.modal-backdrop').css('z-index', '1035');
//         Swal.fire({
//             icon: 'error',
//             title: 'Oops...',
//             text: 'Please select a file to upload!',
//             didClose: () => {
//                 $('.modal').css('z-index', '1055');
//                 $('.modal-backdrop').css('z-index', '1040');
//             }
//         });
//     }

//     let formData = new FormData();
//     formData.append('file', fileInput);

//     console.log(fileInput);
    
//     $('#progressContainer').removeClass('d-none');
//     $('#progressBar').val(0);
//     $('#progressText').text('0%');

//     // checkProgress1();

//     $.ajax({
//         url: '{{ route('import-weather.process') }}',
//         type: 'POST',
//         headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
//         data: formData,
//         processData: false,
//         contentType: false,
//         success: function (data) {
            
//             if (data.success) {
//                 $('#modalCenter').modal('hide');
//                 Swal.fire({
//                     icon: 'success',
//                     title: 'Upload Successful',
//                     text: data.message || 'Your file has been uploaded successfully.',
//                 });
//                 location.reload();
//             } else {
//                 // $('#message').text('Upload failed: ' + data.message).addClass('text-red-500');
//                 Swal.fire({
//                     icon: 'error',
//                     title: 'Upload Failed',
//                     text: data.message || 'There was a problem with your upload.',
//                 });
//             }
//         },
//         error: function () {
//             $('#message').text('An error occurred during upload.').addClass('text-red-500');
//             console.log(error);
//         }
//     });

    
//     function checkProgress() {
//         let interval = setInterval(function () {
//         $.get('{{ route('progress') }}', function (data) {
//             $('#progressBar').val(data.progress);
//             $('#progressText').text(data.progress + '%');
//             console.log('Import Progress:', data.progress + '%');

//             if (data.progress >= 100) {
//                 clearInterval(interval);
//                 $('#message').text('File uploaded successfully!').addClass('text-green-500');
//                 setTimeout(() => location.reload(), 1000); 
//             }
//         });
//     }, 1000); 
//     }

//     function checkProgress1() {
//         let progress = 0;
//         let interval = setInterval(function () {
//         progress += 5; 

//         $('#progressBar').css('width', progress + '%').attr('aria-valuenow', progress);
//         $('#progressText').text(progress + '%');
//         console.log('Import Progress:', progress + '%');

//         if (progress >= 100) {
//             clearInterval(interval);
//         }
//     }, 1500);
// }
// });
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

<!-- Real Time Update -->

 <!-- Pusher JS -->
<script src="https://js.pusher.com/7.2/pusher.min.js"></script>
<!-- Laravel Echo -->
<script src="https://cdn.jsdelivr.net/npm/laravel-echo/dist/echo.iife.js"></script>
<!-- <script>
    Echo.channel('import-progress')
        .listen('FileImportProgress', (e) => {
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'info',
                title: `Importing ${e.fileName}: ${e.progress}% complete`,
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                customClass: {
                    popup: 'bg-primary text-white'
                }
            });
        });
</script> -->

@endsection