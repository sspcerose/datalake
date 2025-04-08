

@extends('layouts/contentNavbarLayout')

@section('title', ' Horizontal Layouts - Forms')

@section('content')

<!-- Basic Layout & Basic with Icons -->
<div class="container d-flex justify-content-center">
  <!-- Basic Layout -->
  <div class="col-md-12">
    <div class="card mb-6">
      <div class="card-header d-flex align-items-center justify-content-between">
        <h5 class="mb-0">Permission</h5> <small class="text-muted float-end"><a href="{{route('user-management')}}">Back</a>
      </div>
      <div class="card-body">
      <!-- Role Selection -->
      <div class="col-md-4">
      <div class="mb-3">
        <label for="roleSelect" class="form-label">Select Role</label>
        <select id="roleSelect" class="form-select">
          <option value="" disabled selected>Select Role</option>
          <option value="admin">Super Admin</option>
          <option value="editor">Admin</option>
          <option value="viewer">Viewer</option>
        </select>
      </div>
</div>

      <!-- Permissions Section -->
      <h6 class="mb-3">Permissions</h6>
      <div class="row">
        <!-- Permission Columns -->

        <div class="col-md-3">
          <h6>Create</h6>
          <div class="form-check">
            <input class="form-check-input" type="checkbox" id="createPost">
            <label class="form-check-label" for="createPost">Table 1</label>
          </div>
          <div class="form-check">
            <input class="form-check-input" type="checkbox" id="createPost">
            <label class="form-check-label" for="createPost">Weather</label>
          </div>
          <div class="form-check">
            <input class="form-check-input" type="checkbox" id="createUser">
            <label class="form-check-label" for="createUser">User</label>
          </div>
        </div>

        <div class="col-md-3">
          <h6>Read</h6>
          <div class="form-check">
            <input class="form-check-input" type="checkbox" id="readPost">
            <label class="form-check-label" for="readPost">Table 1</label>
          </div>
          <div class="form-check">
            <input class="form-check-input" type="checkbox" id="readPost">
            <label class="form-check-label" for="readPost">Weather</label>
          </div>
          <div class="form-check">
            <input class="form-check-input" type="checkbox" id="readUser">
            <label class="form-check-label" for="readUser">User</label>
          </div>
        </div>

        <div class="col-md-3">
          <h6>Update</h6>
          <div class="form-check">
            <input class="form-check-input" type="checkbox" id="updatePost">
            <label class="form-check-label" for="updatePost">Table 1</label>
          </div>
          <div class="form-check">
            <input class="form-check-input" type="checkbox" id="updatePost">
            <label class="form-check-label" for="updatePost">Weather</label>
          </div>
          <div class="form-check">
            <input class="form-check-input" type="checkbox" id="updateUser">
            <label class="form-check-label" for="updateUser">User</label>
          </div>
        </div>

        <div class="col-md-3">
          <h6>Delete</h6>
          <div class="form-check">
            <input class="form-check-input" type="checkbox" id="deletePost">
            <label class="form-check-label" for="deletePost">Table 1</label>
          </div>
          <div class="form-check">
            <input class="form-check-input" type="checkbox" id="deletePost">
            <label class="form-check-label" for="deletePost">Weather</label>
          </div>
          <div class="form-check">
            <input class="form-check-input" type="checkbox" id="deleteUser">
            <label class="form-check-label" for="deleteUser">User</label>
          </div>
        </div>
      </div>

      <!-- Action Buttons -->
      <div class="mt-4">
        <button class="btn btn-warning me-2">Update Permissions</button>
        <a href="#" class="btn btn-outline-secondary">Cancel</a>
      </div>
</div>
         
          
          </div>
        </form>
      </div>
    </div>
  </div>
 
</div>


@endsection
