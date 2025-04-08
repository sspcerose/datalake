@extends('layouts.contentNavbarLayout')

@section('title', 'Roles and Permissions')

@section('content')
<div class="card">
    <h5 class="card-header">Roles and Permissions</h5>  
    <div class="table-responsive text-nowrap">
        <form action="{{ route('permissions.update')}}" method="POST">
            @csrf
            @method('PUT')

            <!-- Table for Roles and Permissions -->
            <table class="table table-bordered" id="allResultsTable">
                <thead class="table-dark">
                    <tr>
                        <th>Table Name</th>
                        <th>Permission Name</th>
                        <th>Super Admin</th>
                        <th>Admin</th>
                        <th>Viewer</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($groupedPermissions as $permissionGroup => $permissions)
                        @php $rowspan = $permissions->count(); @endphp
                        @foreach($permissions as $index => $permission)
                            <tr>
                                @if($index == 0)
                                    <!-- Display permission group for the first row -->
                                    <td rowspan="{{ $rowspan }}" class="text-center align-middle">
                                        {{ $permission->permissionto }}
                                    </td>
                                @endif
                                <td>{{ $permission->name }}</td>

                                <!-- Super Admin Checkbox -->
                                <td>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" name="permissions[super_admin][]" value="{{ $permission->id }}"
                                            @if($roles->firstWhere('name', 'Super Admin')?->permissions->contains($permission)) checked @endif>
                                    </div>
                                </td>

                                <!-- Admin Checkbox -->
                                <td>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" name="permissions[admin][]" value="{{ $permission->id }}"
                                            @if($roles->firstWhere('name', 'Admin')?->permissions->contains($permission)) checked @endif>
                                    </div>
                                </td>

                                <!-- Viewer Checkbox -->
                                <td>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" name="permissions[viewer][]" value="{{ $permission->id }}"
                                            @if($roles->firstWhere('name', 'Viewer')?->permissions->contains($permission)) checked @endif>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    @endforeach
                </tbody>
            </table>

            <!-- Pagination Links -->
            <div class="d-flex justify-content-center mt-3">
                {{ $paginatedPermissions->links() }}
            </div>

            <!-- Action Buttons -->
            <div class="mt-4 mx-3 mb-3">
                <button type="submit" class="btn btn-primary">Update Permissions</button>
                <a href="{{ route('roles.index') }}" class="btn btn-outline-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
