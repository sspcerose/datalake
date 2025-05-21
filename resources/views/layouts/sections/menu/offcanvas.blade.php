<div class="offcanvas offcanvas-start bg-white shadow-sm border-end" tabindex="-1" id="offcanvasStart" aria-labelledby="offcanvasStartLabel" style="width: 350px;">
  <!-- Improved Header -->
  <div class="offcanvas-header border-bottom">
    <div class="d-flex align-items-center">
      <span class="fw-bold fs-4 text-primary">
       DATA LAKE
      </span>
    </div>
    <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
  </div>

  <!-- Original Body Content -->
  <div class="offcanvas-body px-4 py-3">
    @php
      use Illuminate\Support\Facades\DB;

      // Get all tables from the database
      $tables = DB::select("SELECT tablename FROM pg_catalog.pg_tables WHERE schemaname = 'public'");
      $tableNames = array_map(fn($t) => $t->tablename, $tables);

      // Tables to exclude from the sidebar
      $excludedTables = [
        'migrations', 'password_resets', 'failed_jobs', 'personal_access_tokens',
        'password_reset_tokens', 'sessions', 'cache', 'cache_locks', 'job_batches',
        'jobs', 'roles', 'role_permission', 'permissions', 'users', 'jobs_done', 'import_status', 'histories'
      ];

      // Filter out excluded tables
      $filteredTableNames = array_values(array_diff($tableNames, $excludedTables));
      $selectedTable = request()->route('table') ?? ($filteredTableNames[0] ?? null);

      // Process permissions for all tables (not just the selected one)
      foreach ($filteredTableNames as $table) {
        $permissions = ['Create', 'View', 'Update', 'Delete', 'Import', 'Export'];
        $roles = DB::table('roles')->whereIn('name', ['Super Admin', 'Admin', 'Viewer'])->get();

        foreach ($permissions as $action) {
          $permissionName = "{$action} {$table}";
          $permission = DB::table('permissions')->where('name', $permissionName)->first();

          if (!$permission) {
            $permissionId = DB::table('permissions')->insertGetId([
              'name' => $permissionName,
              'permissionto' => $table,
              'created_at' => now(),
              'updated_at' => now(),
            ]);

            // Assign permissions to roles
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
        <li class="mb-2 px-2">
          <button class="w-100 d-flex justify-content-between align-items-center px-3 py-2 rounded border-0 bg-transparent text-start {{ request()->routeIs('user-management') || request()->routeIs('roles.edit') ? 'bg-primary text-white fw-semibold' : 'text-dark' }}"
                  data-bs-toggle="collapse" data-bs-target="#userAccessCollapse" aria-expanded="false" aria-controls="userAccessCollapse">
            <span><i class="bx bx-user-circle me-2 fs-5"></i> User Management</span>
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
        <button class="w-100 d-flex justify-content-between align-items-center px-3 py-2 rounded border-0 bg-transparent text-start {{ request()->routeIs('table.viewer') && $selectedTable ? 'bg-primary text-white fw-semibold' : 'text-dark' }}"
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
                  {{ ucfirst(str_replace('_', ' ', $tbl)) }}
                </a>
              </li>
              @endif
            @endforeach
          </ul>
        </div>
      </li>
    </ul>
  </div>

  <!-- New Footer Section -->
  <div class="offcanvas-footer border-top px-3 py-2">
    <div class="d-flex justify-content-between align-items-center">
      <span class="text-muted"><i class="bx bx-user-circle me-1"></i> {{ auth()->user()->name }}</span>
      <form method="GET" action="{{ route('logout') }}" class="mb-0">
        @csrf
        <button type="submit" class="btn btn-sm btn-outline-danger">
          <i class="bx bx-power-off me-1"></i> Logout
        </button>
      </form>
    </div>
  </div>
</div>