@extends('layouts/contentNavbarLayout')

@section('title', ' Weather Update Form')

@section('content')

<!-- Basic Layout & Basic with Icons -->
<div class="container d-flex justify-content-center">
  <div class="col-md-10">
    <div class="card mb-6">
      <div class="card-header d-flex align-items-center justify-content-between">
        <h5 class="mb-0">Update Weather Data</h5>
        <small class="text-muted float-end"><a href="{{ route('weather.index') }}">Back</a></small>
      </div>
      <div class="card-body">
        <form action="{{ route('weather.update', [$weather->id]) }}" method="POST" id="updateForm">
          @method('PUT')
          @csrf
          <div class="row mb-6">
            <div class="col-md-6">
                <label class="col-form-label" for="city_mun_code">City/Municipality Code</label>
                <input type="number" class="form-control" id="city_mun_code" name="city_mun_code" value="{{ old('city_mun_code', $weather->city_mun_code) }}" placeholder="">
                @error('city_mun_code')
                  <span class="text-danger small font-weight-bold d-block mt-1">{{ $message }}</span>
                @enderror
            </div>
          </div>
          <div class="row mb-6">
            <div class="col-md-4">
                <label class="col-form-label" for="ave_min">Average Min Temperature</label>
                <input type="number" class="form-control" id="ave_min" name="ave_min" value="{{ old('ave_min', $weather->ave_min) }}" placeholder="">
                @error('ave_min')
                  <span class="text-danger small font-weight-bold d-block mt-1">{{ $message }}</span>
                @enderror
            </div>
            <div class="col-md-4">
                <label class="col-form-label" for="ave_max">Average Max Temperature</label>
                <input type="number" class="form-control" id="ave_max" name="ave_max" value="{{ old('ave_max', $weather->ave_max) }}" placeholder="">
                @error('ave_max')
                  <span class="text-danger small font-weight-bold d-block mt-1">{{ $message }}</span>
                @enderror
            </div>
            <div class="col-md-4">
                <label class="col-form-label" for="ave_mean">Average Mean Temperature</label>
                <input type="number" class="form-control" id="ave_mean" name="ave_mean" value="{{ old('ave_mean', $weather->ave_mean) }}" placeholder="">
                @error('ave_mean')
                  <span class="text-danger small font-weight-bold d-block mt-1">{{ $message }}</span>
                @enderror
            </div>
          </div>
          <div class="row mb-6">
            <div class="col-md-6">
                <label class="col-form-label" for="rainfall_mm">Rainfall (mm)</label>
                <select class="form-select" id="rainfall-mm" aria-label="Rainfall mm select" name="rainfall_mm">
                <option value="" disabled {{ !$weather->rainfall_mm ? 'selected' : '' }}>Select Rainfall (mm)</option>
                <option value="0" {{ $weather->rainfall_mm === '0' ? 'selected' : '' }}>0</option>
                <option value="<20" {{ $weather->rainfall_mm === '<20' ? 'selected' : '' }}><20</option>
                <option value="20-40" {{ $weather->rainfall_mm === '20-40' ? 'selected' : '' }}>20-40</option>
                <option value="40-60" {{ $weather->rainfall_mm === '40-60' ? 'selected' : '' }}>40-60</option>
                <option value="<60-80" {{ $weather->rainfall_mm === '<60-80' ? 'selected' : '' }}><60-80</option>
                <option value="80-100" {{ $weather->rainfall_mm === '80-100' ? 'selected' : '' }}>80-100</option>
              </select>
                @error('rainfall_mm')
                  <span class="text-danger small font-weight-bold d-block mt-1">{{ $message }}</span>
                @enderror
            </div>
            <div class="col-md-6">
                <label class="col-form-label" for="rainfall_description">Rainfall Description</label>
                <select class="form-select" id="rainfall-description" aria-label="Rainfall description select" name="rainfall_description">
                <option value="" disabled {{ !$weather->rainfall_description ? 'selected' : '' }}>Select Description</option>
                <option value="NO RAIN" {{ $weather->rainfall_description === 'NO RAIN' ? 'selected' : '' }}>NO RAIN</option>
                <option value="LIGHT RAINS" {{ $weather->rainfall_description === 'LIGHT RAINS' ? 'selected' : '' }}>LIGHT RAINS</option>
                <option value="MODERATE RAINS" {{ $weather->rainfall_description === 'MODERATE RAINS' ? 'selected' : '' }}>MODERATE RAINS</option>
                <option value="HEAVY RAINS" {{ $weather->rainfall_description === 'HEAVY RAINS' ? 'selected' : '' }}>HEAVY RAINS</option>
              </select>
                @error('rainfall_description')
                  <span class="text-danger small font-weight-bold d-block mt-1">{{ $message }}</span>
                @enderror
            </div>
          </div>
          <div class="row mb-6">
            <div class="col-md-6">
                <label class="col-form-label" for="cloud_cover">Cloud Cover</label>
                <select class="form-select" id="cloud-cover" name="cloud_cover">
                <option value="" disabled {{ !$weather->cloud_cover ? 'selected' : '' }}>Select</option>
                <option value="SUNNY" {{ $weather->cloud_cover === 'SUNNY' ? 'selected' : '' }}>SUNNY</option>
                <option value="PARTLY CLOUDY" {{ $weather->cloud_cover === 'PARTLY CLOUDY' ? 'selected' : '' }}>PARTLY CLOUDY</option>
                <option value="CLOUDY" {{ $weather->cloud_cover === 'CLOUDY' ? 'selected' : '' }}>CLOUDY</option>
                <option value="MOSTLY CLOUDY" {{ $weather->cloud_cover === 'MOSTLY CLOUDY' ? 'selected' : '' }}>MOSTLY CLOUDY</option>
              </select>
                @error('cloud_cover')
                  <span class="text-danger small font-weight-bold d-block mt-1">{{ $message }}</span>
                @enderror
            </div>
            <div class="col-md-6">
                <label class="col-form-label" for="humidity">Humidity (%)</label>
                <input type="number" class="form-control" id="humidity" name="humidity" value="{{ old('humidity', $weather->humidity) }}" placeholder="">
                @error('humidity')
                  <span class="text-danger small font-weight-bold d-block mt-1">{{ $message }}</span>
                @enderror
            </div>
          </div>
          <div class="row mb-6">
            <div class="col-md-6">
                <label class="col-form-label" for="forecast_date">Forecast Date</label>
                <input type="date" class="form-control" id="forecast_date" name="forecast_date" value="{{ old('forecast_date', $weather->forecast_date) }}" placeholder="">
                @error('forecast_date')
                  <span class="text-danger small font-weight-bold d-block mt-1">{{ $message }}</span>
                @enderror
            </div>
            <div class="col-md-6">
                <label class="col-form-label" for="date_accessed">Date Accessed</label>
                <input type="date" class="form-control" id="date_accessed" name="date_accessed" value="{{ old('date_accessed', $weather->date_accessed) }}" placeholder="">
                @error('date_accessed')
                  <span class="text-danger small font-weight-bold d-block mt-1">{{ $message }}</span>
                @enderror
            </div>
          </div>
          <div class="row mb-6">
            <div class="col-md-6">
                <label class="col-form-label" for="wind_mps">Wind Speed</label>
                <input type="number" class="form-control" id="wind_mps" name="wind_mps" value="{{ old('wind_mps', $weather->wind_mps) }}" placeholder="">
                @error('forecast_date')
                  <span class="text-danger small font-weight-bold d-block mt-1">{{ $message }}</span>
                @enderror
            </div>
            <div class="col-md-6">
                <label class="col-form-label" for="direction">Direction</label>
                <select class="form-select" id="direction" name="direction">
                <option value="" disabled {{ !$weather->direction ? 'selected' : '' }}>Select Direction</option>
                <option value="N" {{ $weather->direction === 'N' ? 'selected' : '' }}>N</option>
                <option value="NNE" {{ $weather->direction === 'NNE' ? 'selected' : '' }}>NNE</option>
                <option value="NE" {{ $weather->direction === 'NE' ? 'selected' : '' }}>NE</option>
                <option value="ENE" {{ $weather->direction === 'ENE' ? 'selected' : '' }}>ENE</option>
                <option value="E" {{ $weather->direction === 'E' ? 'selected' : '' }}>E</option>
                <option value="ESE" {{ $weather->direction === 'ESE' ? 'selected' : '' }}>ESE</option>
                <option value="SE" {{ $weather->direction === 'SE' ? 'selected' : '' }}>SE</option>
                <option value="SSE" {{ $weather->direction === 'SSE' ? 'selected' : '' }}>SSE</option>
                <option value="S" {{ $weather->direction === 'S' ? 'selected' : '' }}>S</option>
                <option value="SSW" {{ $weather->direction === 'SSW' ? 'selected' : '' }}>SSW</option>
                <option value="SW" {{ $weather->direction === 'SW' ? 'selected' : '' }}>SW</option>
                <option value="WSW" {{ $weather->direction === 'WSW' ? 'selected' : '' }}>WSW</option>
                <option value="W" {{ $weather->direction === 'W' ? 'selected' : '' }}>W</option>
                <option value="WNW" {{ $weather->direction === 'WNW' ? 'selected' : '' }}>WNW</option>
                <option value="NW" {{ $weather->direction === 'NW' ? 'selected' : '' }}>NW</option>
                <option value="NNW" {{ $weather->direction === 'NNW' ? 'selected' : '' }}>NNW</option>
                  </select>
                @error('direction')
                  <span class="text-danger small font-weight-bold d-block mt-1">{{ $message }}</span>
                @enderror
            </div>
          </div>
          <div class="row">
            <div class="col-sm-12 ms-6">
            <div class="mt-6 ms-12">
                <button type="submit" class="btn btn-warning me-3" id="confirmUpdateButton">Update Weather Info</button>
                <a href="{{route('weather.index')}}" class="btn btn-outline-danger">Cancel</a>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Alert Message -->
<!--<script>
  document.getElementById('confirmUpdateButton').addEventListener('click', function() {
    Swal.fire({
      title: "Are you sure?",
      text: "You won't be able to revert this!",
      icon: "warning",
      showCancelButton: true,
      confirmButtonColor: "#3085d6",
      cancelButtonColor: "#d33",
      confirmButtonText: "Yes, update it!"
    }).then((result) => {
      if (result.isConfirmed) {
        // Submit the form immediately after confirmation
        document.getElementById('updateForm').submit();
      }
    });
  });
</script> -->
@endsection
