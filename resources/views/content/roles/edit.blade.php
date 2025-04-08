@extends('layouts/contentNavbarLayout')

@section('title', ' Horizontal Layouts - Forms')

@section('content')

<!-- Basic Layout & Basic with Icons -->
<div class="container d-flex justify-content-center">
  <!-- Basic Layout -->
  <div class="col-md-10">
    <div class="card mb-6">
      <div class="card-header d-flex align-items-center justify-content-between">
        <h5 class="mb-0">Edit Permissions of {{$role->name}}</h5> <small class="text-muted float-end"><a href="{{ route('roles.index') }}">Back</a></small>
      </div>
      <div class="card-body">
        <!-- Permissions Section -->
        <h6 class="mb-3">Permissions</h6>
        <form action="{{ route('roles.update', [$role->id]) }}" method="POST">
          @method('PUT')
          @csrf
          <div class="row mb-6">
            <div class="col-md-6">
                <input type="text" class="form-control" id="basic-default-track-uri" name="name" value="{{ old('name', $role->name) }}" placeholder="" hidden>
          </div>
          @foreach ($permissions->groupBy('permissionto') as $permissionto => $groupedPermissions)
            <h5 class="mx-5">{{ $permissionto }}</h5> <!-- Display the permission group heading -->
            <div class="row mx-5">
              @foreach ($groupedPermissions->chunk(2) as $chunk)
                <div class="col-md-3">
                  @foreach ($chunk as $permission)
                    <div class="form-check">
                      <input class="form-check-input" 
                            type="checkbox" 
                            name="permissions[]" 
                            value="{{ $permission->id }}" 
                            id="permission{{ $permission->id }}"
                            {{ $role->permissions->contains($permission->id) ? 'checked' : '' }}>
                      <label class="form-check-label" for="permission{{ $permission->id }}">
                        {{ $permission->name }}
                      </label>
                    </div>
                  @endforeach
                </div>
              @endforeach
            </div>
            <hr class="mt-2">
          @endforeach
          <!-- Action Buttons -->
          <div class="mt-4">
            <button type="submit" class="btn btn-warning me-2">Update Permissions</button>
            <a href="{{ route('roles.index') }}" class="btn btn-outline-secondary">Cancel</a>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection
