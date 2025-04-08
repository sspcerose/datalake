@extends('layouts/contentNavbarLayout')

@section('title', 'User Details')

@section('content')

<!-- Container for Centering -->
<div class="container d-flex justify-content-center">
  <div class="col-md-10">
    <div class="card mb-6">
      <div class="card-header d-flex align-items-center justify-content-between">
        <h5 class="mb-0">User Account Details</h5>
        <!-- Action Buttons Section -->
        <div class="d-flex align-items-center">
          <!-- Update Button -->
          <a href="{{ route('user.edit', ['user' => $user->id]) }}" class="text-warning me-3" title="Update">
            <i class="bx bx-edit-alt" style="font-size: 1.2rem;"></i>
          </a>
          <!-- Delete Form with SweetAlert Confirmation -->
          <form action="{{ route('user.destroy', [$user->id]) }}" method="POST" id="deleteForm{{ $user->id }}" style="display: inline;">
            @csrf
            @method('DELETE')
            <a href="#" class="text-danger confirmDeleteButton" title="Delete" data-form-id="deleteForm{{ $user->id }}">
              <i class="bx bx-trash" style="font-size: 1.2rem;"></i>
            </a>
          </form>
          <!-- Back Button -->
          <a href="{{ route('user-management') }}" class="text-secondary ms-3" title="Back">
            <i class="bx bx-arrow-back" style="font-size: 1.5rem;"></i>
          </a>
        </div>
      </div>
      <div class="card-body">
        
        <!-- User Information Section -->
        <h6 class="mb-3 fw-bold">User Information</h6>
        <div class="row mb-4">
          <div class="col-md-6">
            <p><strong>Username:</strong> {{ $user->username }}</p>
          </div>
          <div class="col-md-6">
            <p><strong>Email:</strong> {{ $user->email }}</p>
          </div>
        </div>
        <hr class="my-4">
        <!-- Personal Details Section -->
        <h6 class="mb-3 fw-bold">Personal Details</h6>
        <div class="row mb-4">
          <div class="col-md-6">
            <p><strong>First Name:</strong> {{ $user->first_name }}</p>
          </div>
          <div class="col-md-6">
            <p><strong>Last Name:</strong> {{ $user->last_name }}</p>
          </div>
        </div>
        <hr class="my-4">
        <!-- Role and Status Section -->
        <h6 class="mb-3 fw-bold">User Type & Status</h6>
        <div class="row mb-4">
          <div class="col-md-6">
            <p><strong>User Type:</strong> {{ $user->user_type }}</p>
          </div>
          <div class="col-md-6">
            <p><strong>Status:</strong> 
              @if($user->status === 'active')
                <span class="badge bg-label-primary me-1">Active</span>
              @else
                <span class="badge bg-label-danger me-1">Inactive</span>
              @endif
            </p>
          </div>
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
        title: "Delete User?",
        text: "This action is irreversible!",
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
