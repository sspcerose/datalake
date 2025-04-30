@extends('layouts.contentNavbarLayout')

@section('title', 'Permission Table')

@section('content')

<style>
    .nav-pills .nav-link.active {
        color: #fff !important;
        background-color: #696cff !important; /* Optional: for blue background */
        text-decoration: none !important;
    }
</style>

<div class="card">
    <h5 class="card-header">Roles and Permissions</h5>
    <div class="card-body p-0">
        <form action="{{ route('permissions.update') }}" method="POST">
            @csrf
            @method('PUT')

            <div class="d-flex">
                <!-- Sidebar with Tabs -->
                <div class="border ms-3" style="width: 230px;">
                    <!-- Tables Header -->
                    <div class="fw-bold bg-dark py-2 px-3 border-bottom text-white text-center">
                        Tables
                    </div>

                    <!-- Tab Buttons -->
                    <div class="nav flex-column nav-pills w-100" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                        @foreach($groupedPermissions as $group => $permissions)
                            <button
                                class="nav-link text-start rounded-0 {{ $loop->first ? 'active' : '' }}"
                                id="tab-{{ Str::slug($group) }}-tab"
                                data-bs-toggle="pill"
                                data-bs-target="#tab-{{ Str::slug($group) }}"
                                type="button"
                                role="tab"
                                aria-controls="tab-{{ Str::slug($group) }}"
                                aria-selected="{{ $loop->first ? 'true' : 'false' }}"
                                style="
                                    height: 36px;
                                    font-size: 0.85rem;
                                    padding: 0.5rem 1rem;
                                    /* border-bottom: 1px solid #dee2e6; */
                                "
                            >
                                {{ ucfirst(strtolower($group)) }}
                            </button>
                        @endforeach
                    </div>
                </div>

                <!-- Tab Content Area -->
                <div class="flex-grow-1">
                    <div class="tab-content py-0" id="v-pills-tabContent">
                        @foreach($groupedPermissions as $group => $permissions)
                            <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}" id="tab-{{ Str::slug($group) }}" role="tabpanel" aria-labelledby="tab-{{ Str::slug($group) }}-tab">
                                <table class="table table-sm table-bordered mb-0">
                                    <thead class="table-dark">
                                        <tr>
                                            <th style="width: 30%;">Permission</th>
                                            <th style="width: 20%;">Super Admin</th>
                                            <th style="width: 20%;">Admin</th>
                                            <th style="width: 20%;">Viewer</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($permissions as $permission)
                                            <tr>
                                                <td>{{ explode(' ', $permission->name)[0] ?? $permission->name }}</td>
                                                <td>
                                                    <div class="form-check form-switch">
                                                        <input class="form-check-input" type="checkbox" name="permissions[super_admin][]" value="{{ $permission->id }}"
                                                            @if($roles->firstWhere('name', 'Super Admin')?->permissions->contains($permission)) checked @endif>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-check form-switch">
                                                        <input class="form-check-input" type="checkbox" name="permissions[admin][]" value="{{ $permission->id }}"
                                                            @if($roles->firstWhere('name', 'Admin')?->permissions->contains($permission)) checked @endif>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-check form-switch">
                                                        <input class="form-check-input" type="checkbox" name="permissions[viewer][]" value="{{ $permission->id }}"
                                                            @if($roles->firstWhere('name', 'Viewer')?->permissions->contains($permission)) checked @endif>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="p-3 border-top">
                <button type="submit" class="btn btn-primary">Update Permissions</button>
                <a href="{{ route('roles.index') }}" class="btn btn-outline-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
