<div class="offcanvas offcanvas-start bg-white shadow-sm border-end" tabindex="-1" id="offcanvasStart" aria-labelledby="offcanvasStartLabel" style="width: 350px;">
  <div class="offcanvas-header">
    <div class="d-flex align-items-center">
      <span class="fw-bold fs-4 text-primary">DATA LAKE</span>
    </div>
    <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
  </div>

  <div class="offcanvas-body px-4 py-3">
    @php
      use Illuminate\Support\Facades\DB;

      $tables = DB::select("SELECT tablename FROM pg_catalog.pg_tables WHERE schemaname = 'public'");
      $tableNames = array_map(fn($t) => $t->tablename, $tables);

      $excludedTables = [
        'migrations', 'password_resets', 'failed_jobs', 'personal_access_tokens',
        'password_reset_tokens', 'sessions', 'cache', 'cache_locks', 'job_batches',
        'jobs', 'roles', 'role_permission', 'permissions', 'users', 'jobs_done', 'import_status'
      ];

      $filteredTableNames = array_values(array_diff($tableNames, $excludedTables));
      $selectedTable = request()->route('table') ?? ($filteredTableNames[0] ?? null);

      if ($selectedTable) {
      // Define permissions
      $permissions = ['Create', 'View', 'Update', 'Delete', 'Import', 'Export'];

      // Fetch roles
      $roles = DB::table('roles')->whereIn('name', ['Super Admin', 'Admin', 'Viewer'])->get();

      foreach ($permissions as $action) {
        $permissionName = "{$action} {$selectedTable}";
          // Check if the permission already exists
        $permission = DB::table('permissions')->where('name', $permissionName)->first();

        if (!$permission) {
          // Insert the permission if it does not exist
          $permissionId = DB::table('permissions')->insertGetId([
            'name' => $permissionName,
            'permissionto' => $selectedTable,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Assign the permission to roles
        foreach ($roles as $role) {
          if (in_array($role->name, ['Super Admin', 'Admin']) || ($role->name === 'Viewer' && $action === 'View')) {
            DB::table('role_permission')->insert([
              'role_id' => $role->id,
              'permission_id' => $permissionId,
            ]);
          }
        }
      }
    }
  }
  @endphp

  <ul class="list-unstyled fs-6">
   {{-- User Access Accordion --}}
    @if (auth()->user()->hasPermission('View Roles') || auth()->user()->hasPermission('View Users'))
      <!-- <li class="mb-2 px-2">
        <button class="w-100 d-flex justify-content-between align-items-center px-3 py-2 rounded border-0 bg-transparent text-start {{ request()->routeIs('user-management') || request()->routeIs('roles.edit') ? 'bg-primary text-white fw-semibold' : 'text-dark' }}"
                data-bs-toggle="collapse" data-bs-target="#userAccessCollapse" aria-expanded="false" aria-controls="userAccessCollapse">
          <span><i class="bx bx-user-circle me-2 fs-5"></i> User Access</span>
          <i class="bx bx-chevron-down fs-5"></i>
        </button>
      </li> -->
      <li class="mb-2 px-2">
        <button class="w-100 d-flex justify-content-between align-items-center px-3 py-2 rounded border-0 bg-transparent text-start {{ request()->routeIs('user-management') || request()->routeIs('roles.edit') ? 'bg-primary text-white fw-semibold' : 'text-dark' }}"
                data-bs-toggle="collapse" data-bs-target="#userAccessCollapse" aria-expanded="false" aria-controls="userAccessCollapse">
          <span><i class="bx bx-user-circle me-2 fs-5"></i> User Access</span>
          <i class="bx bx-chevron-down fs-5"></i>
        </button>
       
        <div class="collapse mt-1" id="userAccessCollapse">
          <ul class="list-unstyled ps-8">
             @if(auth()->user()->hasPermission('View Users'))
            <li class="mb-1">
              <a href="{{ route('user-management') }}" class="d-block px-2 py-1 rounded {{ request()->routeIs('user-management') ? 'bg-primary text-white fw-semibold' : 'text-dark' }}">
                <i class="bx bx-user me-2"></i> Users
              </a>
            </li>
            @endif
            @if(auth()->user()->hasPermission('View Roles'))
            <li>
              <a href="{{ route('roles.edit', ['role' => 1]) }}" class="d-block px-2 py-1 rounded {{ request()->routeIs('roles.edit') ? 'bg-primary text-white fw-semibold' : 'text-dark' }}">
                <i class="bx bx-lock-alt me-2"></i> Roles & Permissions
              </a>
            </li>
            @endif
          </ul>
        </div>
      </li>
      @endif

      {{-- Tables Accordion --}}
      <li class="mb-2 px-2">
        <button class="w-100 d-flex justify-content-between align-items-center px-3 py-2 rounded border-0 bg-transparent text-start {{ request()->routeIs('table.viewer') ? 'bg-primary text-white fw-semibold' : 'text-dark' }}"
                data-bs-toggle="collapse" data-bs-target="#tablesCollapse" aria-expanded="false" aria-controls="tablesCollapse">
          <span><i class="bx bx-table me-2 fs-5"></i> Tables</span>
          <i class="bx bx-chevron-down fs-5"></i>
        </button>
        <div class="collapse mt-1" id="tablesCollapse">
          <ul class="list-unstyled ps-8">
            @foreach($filteredTableNames as $tbl)

              @if(auth()->user()->hasPermission('View '. $tbl))
              <li class="mb-1">
                <a href="{{ route('table.viewer', ['table' => $tbl]) }}"
                   class="d-block px-2 py-1 rounded {{ $selectedTable === $tbl ? 'bg-primary text-white fw-semibold' : 'text-dark' }}">
                  {{ ucfirst($tbl) }}
                </a>
              </li>
              @endif
            @endforeach
          </ul>
        </div>
      </li>
    </ul>
  </div>
</div>
