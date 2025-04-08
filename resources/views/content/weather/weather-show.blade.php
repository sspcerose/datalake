@extends('layouts/contentNavbarLayout')

@section('title', 'Weather Data Details')

@section('content')
<div class="container d-flex justify-content-center">
  <div class="col-md-10">
    <div class="card shadow-sm mb-5">
      <!-- Card Header with Action Buttons -->
      <div class="card-header d-flex align-items-center justify-content-between">
        <h5 class="mb-0">Weather Data Details</h5>
        <div class="d-flex align-items-center">
          <!-- Update Button -->
          <a href="{{ route('weather.edit', ['weather' => $weather->id]) }}" class="text-warning me-3" title="Update">
            <i class="bx bx-edit-alt" style="font-size: 1.2rem;"></i>
          </a>
          <!-- Delete Form with Confirmation -->
          <form action="{{ route('weather.destroy', [$weather->id]) }}" method="POST" id="deleteForm{{ $weather->id }}" style="display: inline;">
            @csrf
            @method('DELETE')
            <a href="#" class="text-danger confirmDeleteButton" title="Delete" data-form-id="deleteForm{{ $weather->id }}">
              <i class="bx bx-trash" style="font-size: 1.2rem;"></i>
            </a>
          </form>
          <!-- Back Button -->
          <a href="{{ route('weather.index') }}" class="text-secondary ms-3" title="Back">
            <i class="bx bx-arrow-back" style="font-size: 1.5rem;"></i>
          </a>
        </div>
      </div>
      <!-- Card Body -->
      <div class="card-body">
        <!-- Location Details -->
        <h6 class="fw-bold mb-2">Location Details</h6>
        <div class="row mb-4">
          <div class="col-md-6">City/Municipality Code: <strong>{{ $weather->city_mun_code }}</strong></div>
        </div>
        <hr class="my-4">
        <!-- Temperature Details -->
        <h6 class="fw-bold mb-2">Temperature Information</h6>
        <div class="row mb-5">
          <div class="col-md-4">Average Min Temperature: <strong>{{ $weather->ave_min }} °C</strong></div>
          <div class="col-md-4">Average Max Temperature: <strong>{{ $weather->ave_max }} °C</strong></div>
          <div class="col-md-4">Average Mean Temperature: <strong>{{ $weather->ave_mean }} °C</strong></div>
        </div>
        <hr class="my-4">
        <!-- Rainfall Information -->
        <h6 class="fw-bold mb-2">Rainfall Information</h6>
        <div class="row mb-5">
          <div class="col-md-6">Rainfall (mm): <strong>{{ $weather->rainfall_mm }}</strong></div>
          <div class="col-md-6">Rainfall Description: <strong>{{ $weather->rainfall_description }}</strong></div>
        </div>
        <hr class="my-4">
        <!-- Atmospheric Conditions -->
        <h6 class="fw-bold mb-2">Atmospheric Conditions</h6>
        <div class="row mb-5">
          <div class="col-md-6">Cloud Cover: <strong>{{ $weather->cloud_cover }}</strong></div>
          <div class="col-md-6">Humidity: <strong>{{ $weather->humidity }}%</strong></div>
        </div>
        <hr class="my-4">
        <!-- Date Information -->
        <h6 class="fw-bold mb-2">Date Information</h6>
        <div class="row mb-5">
          <div class="col-md-6">Forecast Date: <strong>{{ \Carbon\Carbon::parse($weather->forecast_date)->format('F j, Y') }}</strong></div>
          <div class="col-md-6">Date Accessed: <strong>{{ \Carbon\Carbon::parse($weather->date_accessed)->format('F j, Y') }}</strong></div>
        </div>
        <hr class="my-4">
        <!-- Wind Details -->
        <h6 class="fw-bold mb-2">Wind Details</h6>
        <div class="row mb-5">
          <div class="col-md-6">Wind Speed: <strong>{{ $weather->wind_mps }} mps</strong></div>
          <div class="col-md-6">Direction: <strong>{{ $weather->direction }}</strong></div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Alert Message -->
<script>
  $(document).ready(function () {
    $(document).on('click', '.confirmDeleteButton', function (e) {
      e.preventDefault();
      const formId = $(this).data('form-id');
      const form = $('#' + formId);

      Swal.fire({
        title: "Delete Weather Data?",
        text: "You won't be able to revert this action.",
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
