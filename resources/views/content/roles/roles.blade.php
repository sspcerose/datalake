@extends('layouts/contentNavbarLayout')

@section('title', 'Permission Table')

<!-- For Style -->
@section('content')

<div class="card">
  <h5 class="card-header">Roles and Permission</h5>  
  <div class="table-responsive text-nowrap">
  <div class="container mb-3">
  <div class="row align-items-center">
    <!-- Left Section: Add, Import, Export buttons -->
    <div class="col-md-6 d-flex justify-content-start">
    @if(Auth::user()->user_type != 'User Type 1' && Auth::user()->user_type != 'Viewer' )
        <a class="btn btn-primary btn-sm d-flex align-items-center me-3" href="{{ route('roles.getTable') }}">
            <i class="bx bx-plus-circle me-2"></i> Add
        </a>
        <a class="btn btn-warning btn-sm d-flex align-items-center me-3" href="{{ route('role.edit') }}">
            <i class="bx bx-plus-circle me-2"></i> Edit
        </a>
        @endif
    </div>
    <div class="col-md-6 d-flex justify-content-end">
        
            <!-- Search Input -->
            <input type="text" id="searchInput" placeholder="Search..." class="p-2 border rounded">
        </div>
    </div>
</div>

<!-- Table -->
<table class="table" id="allResultsTable">
    <thead class="table-dark">
        <tr>
        <th><x-sortable-column field="Role" label="Roles" route="roles.index" /></th>
        <th><x-sortable-column field="Permission" label="No. of Permission" route="roles.index" /></th>
        <!-- @if (auth()->user()->hasPermission('Edit Roles') || auth()->user()->hasPermission('Delete Roles'))
        <th style="width: 10%">Actions</th>
        @endif -->
        </tr>
        </thead>
        @if($roles->isEmpty())
        <tbody>
            <tr>
                <td>No roles found.</td>
            </tr>
        </tbody>
    @else
        @foreach ($roles as $role)
        <tbody class="table-border-bottom-0">
            <tr>
                <td class="text-wrap">{{ $role->name }}</td>
                <td class="text-wrap">{{ $role->permissions->count()}}</td>
                
                <!-- @if (auth()->user()->hasPermission('Edit Roles') || auth()->user()->hasPermission('Delete Roles'))
                <td>
                @if (auth()->user()->hasPermission('Edit Roles'))
                    <div class="d-flex align-items-center">
                            <a href="{{ route('roles.edit', ['role' => $role->id]) }}" class="text-warning me-2" title="Update">
                                <i class="bx bx-edit-alt"></i>
                            </a>
                @endif -->
                <!-- @if (auth()->user()->hasPermission('Delete Roles'))
                            <form action="{{ route('roles.destroy', [$role->id]) }}" method="POST" id="deleteForm{{ $role->id }}" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <a href="#" class="text-danger confirmDeleteButton" title="Delete" data-form-id="deleteForm{{ $role->id }}">
                                    <i class="bx bx-trash"></i>
                                </a>
                            </form>
                        </div>
                    @endif -->
                    </td>
                @endif
            </tr>

        @endforeach
        </tbody>
    </table>
    @endif
<div id="allpagination"class="mt-4">
      {{ $roles->links(('vendor.pagination.bootstrap-5')) }}
</div>

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


@endsection
