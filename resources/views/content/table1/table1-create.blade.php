@extends('layouts/contentNavbarLayout')

@section('title', ' Horizontal Layouts - Forms')

@section('content')

<!-- Basic Layout & Basic with Icons -->
<div class="container d-flex justify-content-center">
  <!-- Basic Layout -->
  <div class="col-md-10">
    <div class="card mb-6">
      <div class="card-header d-flex align-items-center justify-content-between">
        <h5 class="mb-0">Add Sample</h5> <small class="text-muted float-end"><a href="{{route('dashboard-analytics')}}">Back</a>
      </div>
      <div class="card-body">
        <form action="{{ route('table1.store') }}" method="POST" id="addForm">
          @csrf
          <div class="row mb-6">
            <div class="col-md-6">
                <label class="col-form-label" for="basic-default-track-uri">Track URI</label>
                <input type="text" class="form-control" id="basic-default-track-uri" name="track_uri" value="{{ old('track_uri')}}" placeholder="" />
                @error('track_uri')
                <span class="text-danger small font-weight-bold d-block mt-1">{{ $message }}</span>
                @enderror
            </div>
            <div class="col-md-6">
                <label class="col-form-label" for="basic-default-time">Timestamps</label>
                <input type="text" class="form-control" id="basic-default-time" name="t_time" placeholder="" />
                @error('t_time')
                <span class="text-danger small font-weight-bold d-block mt-1">{{ $message }}</span>
                @enderror
            </div>
        </div>
        <div class="row mb-6">
          <div class="col-md-6">
            <label class="col-form-label" for="basic-default-company">Platform</label>
              <select class="form-select" id="basic-default-company" aria-label="Default select example" name="platform">
              <option value="" disabled selected>Select a platform</option>
              <option value="web player" {{ old('platform') == 'web player' ? 'selected' : '' }}>Web Player</option>
              <option value="windows" {{ old('platform') == 'windows' ? 'selected' : '' }}>Windows</option>
              <option value="android" {{ old('platform') == 'android' ? 'selected' : '' }}>Android</option>
              <option value="iOS" {{ old('platform') == 'iOS' ? 'selected' : '' }}>iOS</option>
              </select>
            </div>
          <div class="col-md-6">
            <label class="col-form-label" for="basic-default-ms_played">MS Played</label>
              <input type="number" class="form-control" id="basic-default-ms_played" name="ms_played" placeholder="" />
            </div>
          </div>
          <div class="row mb-6">
          <div class="col-md-6">
            <label class="col-form-label" for="basic-default-track_name">Track Name</label>
              <input type="text" class="form-control" id="basic-default-track_name" name="track_name" placeholder="" />
            </div>
        </div>
        <div class="row mb-6">
            <div class="col-md-6">
                <label class="col-form-label" for="basic-default-artist_name">Artist Name</label>
                <input type="text" class="form-control" id="basic-default-artist_name" name="artist_name" placeholder="" />
            </div>
            <div class="col-md-6">
                <label class="col-form-label" for="basic-default-album_name">Album Name</label>
                <input type="text" class="form-control" id="basic-default-album_name" name="album_name" placeholder="" />
            </div>
        </div>
          <div class="row mb-6">
          <div class="col-md-6">
            <label class="col-form-label" for="basic-default-company">Reason Start</label>
            <select class="form-select" id="basic-default-company" aria-label="Default select example" name="reason_start">
              <option value="" disabled selected>Reason Start</option>
              <option value="autoplay" {{ old('reason_start') == 'autoplay' ? 'selected' : '' }}>autoplay</option>
              <option value="clickrow" {{ old('reason_start') == 'clickrow' ? 'selected' : '' }}>clickrow</option>
              <option value="trackdone" {{ old('reason_start') == 'trackdone' ? 'selected' : '' }}>trackdone</option>
              <option value="nextbtn" {{ old('reason_start') == 'nextbtn' ? 'selected' : '' }}>nextbtn</option>
              <option value="popup" {{ old('reason_start') == 'popup' ? 'selected' : '' }}>popup</option>
              <option value="appload" {{ old('reason_start') == 'appload' ? 'selected' : '' }}>appload</option>
              <option value="unknown" {{ old('reason_start') == 'unknown' ? 'selected' : '' }}>unknown</option>
              <option value="fwdbtn" {{ old('reason_start') == 'fwdbtn' ? 'selected' : '' }}>fwdbtn</option>
            </select>
          </div>
          <div class="col-md-6">
            <label class=" col-form-label" for="basic-default-company">Reason End</label>
            <select class="form-select" id="basic-default-company" aria-label="Default select example" name="reason_end">
              <option value="" disabled selected>Reason End</option>
              <option value="autoplay" {{ old('reason_end') == 'autoplay' ? 'selected' : '' }}>autoplay</option>
              <option value="clickrow" {{ old('reason_end') == 'clickrow' ? 'selected' : '' }}>clickrow</option>
              <option value="trackdone" {{ old('reason_end') == 'trackdone' ? 'selected' : '' }}>trackdone</option>
              <option value="nextbtn" {{ old('reason_end') == 'nextbtn' ? 'selected' : '' }}>nextbtn</option>
              <option value="popup" {{ old('reason_end') == 'popup' ? 'selected' : '' }}>popup</option>
              <option value="appload" {{ old('reason_end') == 'appload' ? 'selected' : '' }}>appload</option>
              <option value="unknown" {{ old('reason_end') == 'unknown' ? 'selected' : '' }}>unknown</option>
              <option value="fwdbtn" {{ old('reason_end') == 'fwdbtn' ? 'selected' : '' }}>fwdbtn</option>
              <option value="endplay" {{ old('reason_end') == 'endplay' ? 'selected' : '' }}>endplay</option>
            </select>
            </div>
          </div>
          <div class="row mb-6">
          <div class="col-md-6">
            <label class="col-form-label" for="basic-default-company">Shuffle</label>
            <select class="form-select" id="basic-default-company" aria-label="Default select example" name="shuffle">
              <option value="" disabled selected>Shuffle</option>
              <option value="TRUE" {{ old('shuffle') == 'TRUE' ? 'selected' : '' }}>TRUE</option>
              <option value="FALSE" {{ old('shuffle') == 'FALSE' ? 'selected' : '' }}>FALSE</option>
            </select>
            </div>
          <div class="col-md-6">
            <label class="col-form-label" for="basic-default-company">Skipped</label>
            <select class="form-select" id="basic-default-company" aria-label="Default select example" name="skipped">
              <option value="" disabled selected>Shuffle</option>
              <option value="TRUE" {{ old('shuffle') == 'TRUE' ? 'selected' : '' }}>TRUE</option>
              <option value="FALSE" {{ old('shuffle') == 'FALSE' ? 'selected' : '' }}>FALSE</option>
          </select>
            </div>
        </div>
          <div class="row">
            <div class="col-sm-12 ms-6">
              <div class="mt-6 ms-12">
                <button type="submit" class="btn btn-primary me-3" id="comfirmAddButton">Add Sample</button>
                <a href="{{route('dashboard-analytics')}}" class="btn btn-outline-danger">Cancel</a>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Alert Message -->
<!-- <script>
  document.getElementById('confirmAddButton').addEventListener('click', function() {
    Swal.fire({
      title: "Add New Sample?",
      text: "You won't be able to revert this!",
      icon: "warning",
      showCancelButton: true,
      confirmButtonColor: "#3085d6",
      cancelButtonColor: "#d33",
      confirmButtonText: "Yes, add it!"
    }).then((result) => {
      if (result.isConfirmed) {
        // Submit the form immediately after confirmation
        document.getElementById('addForm').submit();
      }
    });
  });
</script> -->

<!-- form validator -->

@endsection
