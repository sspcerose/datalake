@extends('layouts/contentNavbarLayout')

@section('title', ' Horizontal Layouts - Forms')

@section('content')

<!-- Basic Layout & Basic with Icons -->
<div class="container d-flex justify-content-center">
  <!-- Basic Layout -->
  <div class="col-md-10">
    <div class="card mb-6">
      <div class="card-header d-flex align-items-center justify-content-between">
        <h5 class="mb-0">Update Sample</h5> <small class="text-muted float-end"><a href="{{route('dashboard-analytics')}}">Back</a>
      </div>
      <div class="card-body">
      <form action="{{ route('table1.update', [$table1->id]) }}" id="updateForm" method="POST">
        @method('PUT')
        @csrf
          <div class="row mb-6">
            <div class="col-md-6">
                <label class="col-form-label" for="basic-default-track-uri">Track URI</label>
                <input type="text" class="form-control" id="basic-default-track-uri" name="track_uri" value="{{ old('track_uri', $table1->track_uri) }}" placeholder="">
                @error('track_uri') 
                  <span class="text-danger small font-weight-bold d-block mt-1">{{ $message }}</span>
                @enderror
            </div>
            <div class="col-md-6">
                <label class="col-form-label" for="basic-default-time">Timestamps</label>
                <input type="text" class="form-control" id="basic-default-time" name="t_time" value="{{ old('t_time', $table1->t_time) }}" placeholder="">
                @error('t_time')
                  <span class="text-danger small font-weight-bold d-block mt-1">{{ $message }}</span>
                @enderror
            </div>
          </div>
          <div class="row mb-6">
          <div class="col-md-6">
            <label class="col-form-label" for="basic-default-company">Platform</label>
              <select class="form-select" id="basic-default-company" aria-label="Default select example" name="platform">
              <option value="" disabled {{ !$table1->platform ? 'selected' : '' }}>Select Platform</option>
              <option value="web player" {{ $table1->platform === 'web player' ? 'selected' : '' }}>Web player</option>
              <option value="windows" {{ $table1->platform === 'windows' ? 'selected' : '' }}>Windows</option>
              <option value="android" {{ $table1->platform === 'android' ? 'selected' : '' }}>Android</option>
              <option value="android" {{ $table1->platform === 'android' ? 'selected' : '' }}>Android</option>
              </select>
              @error('platform')
                  <span class="text-danger small font-weight-bold d-block mt-1">{{ $message }}</span>
              @enderror
            </div>
          <div class="col-md-6">
            <label class="col-form-label" for="basic-default-ms_played">MS Played</label>
              <input type="number" class="form-control" id="basic-default-ms_played" name="ms_played" value="{{ old('ms_played', $table1->ms_played) }}" placeholder="" />
              @error('ms_played')
                  <span class="text-danger small font-weight-bold d-block mt-1">{{ $message }}</span>
              @enderror
            </div>
          </div>
          <div class="row mb-6">
          <div class="col-md-6">
            <label class="col-form-label" for="basic-default-track_name">Track Name</label>
              <input type="text" class="form-control" id="basic-default-track_name" name="track_name" value="{{ old('track_name', $table1->track_name) }}" placeholder="" />
              @error('track_name')
                  <span class="text-danger small font-weight-bold d-block mt-1">{{ $message }}</span>
              @enderror
            </div>
        </div>
        <div class="row mb-6">
            <div class="col-md-6">
                <label class="col-form-label" for="basic-default-artist_name">Artist Name</label>
                <input type="text" class="form-control" id="basic-default-artist_name" name="artist_name" value="{{ old('artist_name', $table1->artist_name) }}" placeholder="" />
                @error('artist_name')
                  <span class="text-danger small font-weight-bold d-block mt-1">{{ $message }}</span>
                @enderror
            </div>
            <div class="col-md-6">
                <label class="col-form-label" for="basic-default-album_name">Album Name</label>
                <input type="text" class="form-control" id="basic-default-album_name" name="album_name" value="{{ old('album_name', $table1->album_name) }}" placeholder="" />
                @error('album_name')
                  <span class="text-danger small font-weight-bold d-block mt-1">{{ $message }}</span>
                @enderror
            </div>
          </div>
          <div class="row mb-6">
          <div class="col-md-6">
            <label class="col-form-label" for="basic-default-company">Reason Start</label>
            <select class="form-select" id="basic-default-company" aria-label="Default select example" name="reason_start">
              <option value="" disabled {{ !$table1->reason_start ? 'selected' : '' }}>Reason Start</option>
              <option value="autoplay" {{ $table1->reason_start == 'autoplay' ? 'selected' : '' }}>autoplay</option>
              <option value="clickrow" {{ $table1->reason_start == 'clickrow' ? 'selected' : '' }}>clickrow</option>
              <option value="trackdone" {{ $table1->reason_start == 'trackdone' ? 'selected' : '' }}>trackdone</option>
              <option value="nextbtn" {{ $table1->reason_start == 'nextbtn' ? 'selected' : '' }}>nextbtn</option>
              <option value="popup" {{ $table1->reason_start == 'popup' ? 'selected' : '' }}>popup</option>
              <option value="appload" {{ $table1->reason_start == 'appload' ? 'selected' : '' }}>appload</option>
              <option value="unknown" {{ $table1->reason_start == 'unknown' ? 'selected' : '' }}>unknown</option>
              <option value="fwdbtn" {{ $table1->reason_start == 'fwdbtn' ? 'selected' : '' }}>fwdbtn</option>
            </select>
            @error('reason_start')
                  <span class="text-danger small font-weight-bold d-block mt-1">{{ $message }}</span>
            @enderror
          </div>
          <div class="col-md-6">
            <label class=" col-form-label" for="basic-default-company">Reason End</label>
            <select class="form-select" id="basic-default-company" aria-label="Default select example" name="reason_end">
            <option value="" disabled {{ !$table1->reason_end ? 'selected' : '' }}>Reason End</option>
              <option value="autoplay" {{ $table1->reason_end == 'autoplay' ? 'selected' : '' }}>autoplay</option>
              <option value="clickrow" {{ $table1->reason_end == 'clickrow' ? 'selected' : '' }}>clickrow</option>
              <option value="trackdone" {{ $table1->reason_end == 'trackdone' ? 'selected' : '' }}>trackdone</option>
              <option value="nextbtn" {{ $table1->reason_end == 'nextbtn' ? 'selected' : '' }}>nextbtn</option>
              <option value="popup" {{ $table1->reason_end == 'popup' ? 'selected' : '' }}>popup</option>
              <option value="appload" {{ $table1->reason_end == 'appload' ? 'selected' : '' }}>appload</option>
              <option value="unknown" {{ $table1->reason_end == 'unknown' ? 'selected' : '' }}>unknown</option>
              <option value="fwdbtn" {{ $table1->reason_end == 'fwdbtn' ? 'selected' : '' }}>fwdbtn</option>
              <option value="endplay" {{ $table1->reason_end == 'endplay' ? 'selected' : '' }}>endplay</option>
            </select>
            @error('reason_end')
                  <span class="text-danger small font-weight-bold d-block mt-1">{{ $message }}</span>
            @enderror
            </div>
          </div>
          <div class="row mb-6">
          <div class="col-md-6">
            <label class="col-form-label" for="basic-default-company">Shuffle</label>
            <select class="form-select" id="basic-default-company" aria-label="Default select example" name="shuffle">
              <option value="" disabled {{ !$table1->shuffle ? 'selected' : '' }}>Shuffle</option>
              <option value="TRUE" {{ $table1->shuffle == 'TRUE' ? 'selected' : '' }}>TRUE</option>
              <option value="FALSE" {{ $table1->shuffle == 'FALSE' ? 'selected' : '' }}>FALSE</option>  
            </select>
            @error('shuffle')
                  <span class="text-danger small font-weight-bold d-block mt-1">{{ $message }}</span>
            @enderror
            </div>
          <div class="col-md-6">
            <label class="col-form-label" for="basic-default-company">Skipped</label>
            <select class="form-select" id="basic-default-company" aria-label="Default select example" name="skipped">
              <option value="" disabled {{ !$table1->skipped ? 'selected' : '' }}>Skipped</option>
              <option value="TRUE" {{ $table1->skipped == 'TRUE' ? 'selected' : '' }}>TRUE</option>
              <option value="FALSE" {{ $table1->skipped == 'FALSE' ? 'selected' : '' }}>FALSE</option>
            </select>
            @error('skipped')
                  <span class="text-danger small font-weight-bold d-block mt-1">{{ $message }}</span>
            @enderror
            </div>
        </div>
          <div class="row">
            <div class="col-sm-12 ms-6">
            <div class="mt-6 ms-12">
                <button type="submit" class="btn btn-warning me-3" id="confirmUpdateButton">Update Sample</button>
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
</script>  -->
@endsection
