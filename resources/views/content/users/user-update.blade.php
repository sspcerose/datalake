@extends('layouts/contentNavbarLayout')

@section('title', ' Horizontal Layouts - Forms')

@section('content')

<!-- Basic Layout & Basic with Icons -->
<div class="container d-flex justify-content-center">
  <!-- Basic Layout -->
  <div class="col-md-8">
    <div class="card mb-6">
      <div class="card-header d-flex align-items-center justify-content-between">
        <h5 class="mb-0">Update User</h5> <small class="text-muted float-end"><a href="{{route('user-management')}}">Back</a>
      </div>
      <div class="card-body">
        <form action="{{ route('user.update', [$user->id]) }}" method="POST" id="updateForm">
        @method('PUT')
        @csrf
          <div class="row mb-6">
            <label class="col-sm-2 col-form-label" for="basic-default-username">User Name</label>
            <div class="col-sm-10">
              <input type="text" class="form-control" id="basic-default-username" name="username" placeholder="John Doe" value="{{ old('username', $user->username) }}" readonly/>
              @error('username')
                  <span class="text-danger small font-weight-bold d-block mt-1">{{ $message }}</span>
              @enderror
            </div>
          </div>
          <div class="row mb-6">
            <label class="col-sm-2 col-form-label" for="basic-default-first_name">First Name</label>
            <div class="col-sm-10">
              <input type="text" class="form-control" id="basic-default-first_name" name="first_name" placeholder="John Doe" value="{{ old('first_name', $user->first_name) }}" readonly/>
              @error('first_name')
                  <span class="text-danger small font-weight-bold d-block mt-1">{{ $message }}</span>
              @enderror
            </div>
          </div>
          <div class="row mb-6">
            <label class="col-sm-2 col-form-label" for="basic-default-last_name">Last Name</label>
            <div class="col-sm-10">
              <input type="text" class="form-control" id="basic-default-last_name" name="last_name" placeholder="John Doe" value="{{ old('last_name', $user->last_name) }}" readonly/>
              @error('last_name')
                  <span class="text-danger small font-weight-bold d-block mt-1">{{ $message }}</span>
              @enderror
            </div>
          </div>
          <div class="row mb-6">
            <label class="col-sm-2 col-form-label" for="basic-default-company">User Type</label>
            <div class="col-sm-10">
              <select class="form-select" id="basic-default-company" aria-label="Default select example" name="user_type">
              <option value="" disabled {{ !$user->user_type ? 'selected' : '' }}>Select User Type</option>
              <option value="Super Admin" {{ old('user_type') == 'Super Admin' ? 'selected' : '' }}>Super Admin</option>
              <option value="Admin" {{ old('user_type') == 'Admin' ? 'selected' : '' }}>Admin</option>
              <option value="Viewer" {{ old('user_type') == 'Viewer' ? 'selected' : '' }}>Viewer</option>
              </select>
              @error('user_type')
                  <span class="text-danger small font-weight-bold d-block mt-1">{{ $message }}</span>
              @enderror
            </div>
          </div>
          <div class="row mb-6">
            <label class="col-sm-2 col-form-label" for="basic-default-email">Email</label>
            <div class="col-sm-10">
              <div class="input-group input-group-merge">
                <input type="text" name="email" value="{{ old('email', $user->email) }}" id="basic-default-email" class="form-control" placeholder="john.doe" aria-label="john.doe" aria-describedby="basic-default-email2" readonly/>
              </div>
              <div class="form-text"> You can use letters, numbers & periods </div>
              @error('email')
                  <span class="text-danger small font-weight-bold d-block mt-1">{{ $message }}</span>
              @enderror
            </div>
          </div>
        <div class="row mb-6">
            <label class="col-sm-2 col-form-label" for="basic-default-company">Status</label>
            <div class="col-sm-10">
              <select class="form-select" id="basic-default-company" aria-label="Default select example" name="status">
                <option value="" disabled {{ !$user->status ? 'selected' : '' }}>Select Status</option>
                <option value="active" {{ $user->status === 'active' ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ $user->status === 'inactive' ? 'selected' : '' }}>Inactive</option>
              </select>
              @error('status')
                  <span class="text-danger small font-weight-bold d-block mt-1">{{ $message }}</span>
              @enderror
            </div>
          </div>
          <div class="row justify-content-end">
            <div class="col-sm-10">
              <div class="mt-6">
                <button type="submit" id="confirmUpdateButton" class="btn btn-warning me-3">Save changes</button>
                <a href="{{route('user-management')}}" class="btn btn-outline-danger">Cancel</a>
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
      title: "Update User?",
      text: "You won't be able to revert this!",
      icon: "warning",
      showCancelButton: true,
      confirmButtonColor: "#3085d6",
      cancelButtonColor: "#d33",
      confirmButtonText: "Yes, update it!"
    }).then((result) => {
      if (result.isConfirmed) {
        document.getElementById('updateForm').submit();
      }
    });
  });
</script> -->
@endsection
