@extends('layouts/contentNavbarLayout')

@section('title', 'Add Weather Data')

@section('content')

<div class="container d-flex justify-content-center">
  <div class="col-md-10">
    <div class="card mb-6">
      <div class="card-header d-flex align-items-center justify-content-between">
        <h5 class="mb-0">Add Weather Data</h5> 
        <small class="text-muted float-end"><a href="{{ route('dashboard-analytics') }}">Back</a></small>
      </div>
      <div class="card-body">
        <form action="{{ route('weather.store') }}" method="POST" id="addWeatherForm">
          @csrf
          <div class="row mb-6">
            <div class="col-md-6">
              <label class="col-form-label" for="city_mun_code">City/Municipality Code</label>
              <input type="number" class="form-control" id="city_mun_code" name="city_mun_code" value="{{ old('city_mun_code') }}" placeholder="">
              @error('city_mun_code')
                <span class="text-danger small font-weight-bold d-block mt-1">{{ $message }}</span>
              @enderror
            </div>
          </div>
          <div class="row mb-6">
            <div class="col-md-4">
              <label class="col-form-label" for="ave_min">Average Min Temperature</label>
              <input type="number" class="form-control" id="ave_min" name="ave_min" value="{{ old('ave_min') }}" placeholder="">
              @error('ave_min')
                <span class="text-danger small font-weight-bold d-block mt-1">{{ $message }}</span>
              @enderror
            </div>
            <div class="col-md-4">
              <label class="col-form-label" for="ave_max">Average Max Temperature</label>
              <input type="number" class="form-control" id="ave_max" name="ave_max" value="{{ old('ave_max') }}" placeholder="">
              @error('ave_max')
                <span class="text-danger small font-weight-bold d-block mt-1">{{ $message }}</span>
              @enderror
            </div>
            <div class="col-md-4">
              <label class="col-form-label" for="ave_mean">Average Mean Temperature</label>
              <input type="number" class="form-control" id="ave_mean" name="ave_mean" value="{{ old('ave_mean') }}" placeholder="">
              @error('ave_mean')
                <span class="text-danger small font-weight-bold d-block mt-1">{{ $message }}</span>
              @enderror
            </div>
          </div>
          <div class="row mb-3">
            <div class="col-sm-6">
              <label class="form-label" for="rainfall-mm">Rainfall (mm)</label>
              <select class="form-select" id="rainfall-mm" aria-label="Rainfall mm select" name="rainfall_mm">
                <option value="0" {{ old('rainfall_mm') == '0' ? 'selected' : '' }}>0</option>
                <option value="<20" {{ old('rainfall_mm') == '<20' ? 'selected' : '' }}>&lt;20</option>
                <option value="20-40" {{ old('rainfall_mm') == '20-40' ? 'selected' : '' }}>20-40</option>
                <option value="40-60" {{ old('rainfall_mm') == '40-60' ? 'selected' : '' }}>40-60</option>
                <option value="<60-80" {{ old('rainfall_mm') == '<60-80' ? 'selected' : '' }}>&lt;60-80</option>
                <option value="80-100" {{ old('rainfall_mm') == '80-100' ? 'selected' : '' }}>80-100</option>
              </select>
              @error('rainfall_mm')
                <span class="text-danger small font-weight-bold d-block mt-1">{{ $message }}</span>
              @enderror
            </div>
            <div class="col-sm-6">
              <label class="form-label" for="rainfall-description">Rainfall Description</label>
              <select class="form-select" id="rainfall-description" aria-label="Rainfall description select" name="rainfall_description">
                <option value="NO RAIN" {{ old('rainfall_description') == 'NO RAIN' ? 'selected' : '' }}>NO RAIN</option>
                <option value="LIGHT RAINS" {{ old('rainfall_description') == 'LIGHT RAINS' ? 'selected' : '' }}>LIGHT RAINS</option>
                <option value="MODERATE RAINS" {{ old('rainfall_description') == 'MODERATE RAINS' ? 'selected' : '' }}>MODERATE RAINS</option>
                <option value="HEAVY RAINS" {{ old('rainfall_description') == 'HEAVY RAINS' ? 'selected' : '' }}>HEAVY RAINS</option>
              </select>
              @error('rainfall_description')
                <span class="text-danger small font-weight-bold d-block mt-1">{{ $message }}</span>
              @enderror
            </div>
          </div>
          <div class="row mb-3">
            <div class="col-md-6">
              <label class="form-label" for="cloud-cover">Cloud Cover</label>
              <select class="form-select" id="cloud-cover" name="cloud_cover">
                <option value="CLOUDY" {{ old('cloud_cover') == 'CLOUDY' ? 'selected' : '' }}>CLOUDY</option>
                <option value="MOSTLY CLOUDY" {{ old('cloud_cover') == 'MOSTLY CLOUDY' ? 'selected' : '' }}>MOSTLY CLOUDY</option>
                <option value="PARTLY CLOUDY" {{ old('cloud_cover') == 'PARTLY CLOUDY' ? 'selected' : '' }}>PARTLY CLOUDY</option>
                <option value="SUNNY" {{ old('cloud_cover') == 'SUNNY' ? 'selected' : '' }}>SUNNY</option>
              </select>
              @error('cloud_cover')
                <span class="text-danger small font-weight-bold d-block mt-1">{{ $message }}</span>
              @enderror
            </div>
            <div class="col-md-6">
              <label class="form-label" for="humidity">Humidity (%)</label>
              <input type="number" class="form-control" id="humidity" name="humidity" step="1" value="{{ old('humidity') }}" placeholder="Enter humidity" />
              @error('humidity')
                <span class="text-danger small font-weight-bold d-block mt-1">{{ $message }}</span>
              @enderror
            </div>
          </div>
          <div class="row mb-3">
            <div class="col-md-6">
              <label class="col-form-label" for="forecast-date">Forecast Date</label>
              <input type="date" class="form-control" id="forecast-date" name="forecast_date" value="{{ old('forecast_date') }}" />
              @error('forecast_date')
                <span class="text-danger small font-weight-bold d-block mt-1">{{ $message }}</span>
              @enderror
            </div>
            <div class="col-md-6">
              <label class="col-form-label" for="date-accessed">Date Accessed</label>
              <input type="date" class="form-control" id="date-accessed" name="date_accessed" value="{{ old('date_accessed') }}" />
              @error('date_accessed')
                <span class="text-danger small font-weight-bold d-block mt-1">{{ $message }}</span>
              @enderror
            </div>
          </div>
          <div class="row mb-3">
            <div class="col-md-6">
              <label class="col-form-label" for="wind-mps">Wind Speed (mps)</label>
              <input type="number" class="form-control" id="wind-mps" name="wind_mps" step="0.1" value="{{ old('wind_mps') }}" placeholder="Enter wind speed" />
              @error('wind_mps')
                <span class="text-danger small font-weight-bold d-block mt-1">{{ $message }}</span>
              @enderror
            </div>
            <div class="col-md-6">
              <label class="col-form-label" for="direction">Wind Direction</label>
                <select class="form-select" id="direction" name="direction">
                  <option value="N" {{ old('direction') == 'N' ? 'selected' : '' }}>N</option>
                  <option value="NNE" {{ old('direction') == 'NNE' ? 'selected' : '' }}>NNE</option>
                  <option value="NE" {{ old('direction') == 'NE' ? 'selected' : '' }}>NE</option>
                  <option value="ENE" {{ old('direction') == 'ENE' ? 'selected' : '' }}>ENE</option>
                  <option value="E" {{ old('direction') == 'E' ? 'selected' : '' }}>E</option>
                  <option value="ESE" {{ old('direction') == 'ESE' ? 'selected' : '' }}>ESE</option>
                  <option value="SE" {{ old('direction') == 'SE' ? 'selected' : '' }}>SE</option>
                  <option value="SSE" {{ old('direction') == 'SSE' ? 'selected' : '' }}>SSE</option>
                  <option value="S" {{ old('direction') == 'S' ? 'selected' : '' }}>S</option>
                  <option value="SSW" {{ old('direction') == 'SSW' ? 'selected' : '' }}>SSW</option>
                  <option value="SW" {{ old('direction') == 'SW' ? 'selected' : '' }}>SW</option>
                  <option value="WSW" {{ old('direction') == 'WSW' ? 'selected' : '' }}>WSW</option>
                  <option value="W" {{ old('direction') == 'W' ? 'selected' : '' }}>W</option>
                  <option value="WNW" {{ old('direction') == 'WNW' ? 'selected' : '' }}>WNW</option>
                  <option value="NW" {{ old('direction') == 'NW' ? 'selected' : '' }}>NW</option>
                  <option value="NNW" {{ old('direction') == 'NNW' ? 'selected' : '' }}>NNW</option>
                </select>
                  @error('direction')
                    <span class="text-danger small font-weight-bold d-block mt-1">{{ $message }}</span>
                  @enderror
                </div>
              </div>
            <div class="mt-4">
              <button type="submit" id="confirmAddButton" class="btn btn-primary me-3">Add Weather Data</button>
              <a href="{{ route('dashboard-analytics') }}" class="btn btn-outline-danger">Cancel</a>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

@endsection