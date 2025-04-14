<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Support\Facades\DB;

class RoleController extends Controller
{

        public function index()
        {
            $roles = Role::with('permissions')->paginate(5);
            $permissions = Permission::all();
            return view('content.roles.roles', compact('roles', 'permissions'));
        }
    
        public function create()
        {
            // $permissions = Permission::all();
            // return view('content.roles.role-create', compact('permissions'));
            $permissiontoOptions = Permission::pluck('permissionto')->unique();
            return view('content.roles.role-create', compact('permissiontoOptions'));
        }

        // JASHLIE
        public function getTable()
        {
            // Tables to exclude
            $excludedTables = ['migrations', 'password_resets', 'password_reset_tokens', 'failed_jobs', 'cache', 'cache_locks', 'job_batches', 'jobs', 'sessions','permissions','role_permission','samples'];

            // Fetch table names from PostgreSQL while excluding unwanted tables
            $tables = DB::select("
                SELECT tablename 
                FROM pg_tables 
                WHERE schemaname = 'public' 
                AND tablename NOT IN ('" . implode("','", $excludedTables) . "')
            ");

            // Convert objects to a simple array of table names
            $tableNames = array_map(fn($table) => $table->tablename, $tables);

            $permissions = DB::table('permissions')->pluck('name')->toArray();
            
            return view('content.roles.role-create_new', compact('tableNames','permissions'));
        }
    
        public function store(Request $request)
        {
            // dd($request->all());
            // $validatedData = $request->validate([
            //     'permissions.*' => 'required|string|unique:permissions,name',
            //     'permissionto' => 'required|string',
            //     'new_permissionto' => 'nullable|string|unique:permissions,permissionto',
            // ]);
            // // dd($validatedData);

            // if ($request->input('permissionto') === 'add-new' && $request->filled('new_permissionto')) {
            //     $validatedData['permissionto'] = $request->input('new_permissionto');
            // }

            // foreach ($validatedData['permissions'] as $permissionName) {
            //     Permission::create([
            //         'permissionto' => $validatedData['permissionto'],
            //         'name' => $permissionName,
            //     ]);
            // }

            // return redirect()->route('roles.index')->with('success', 'Permissions added successfully!');
            $tables = $request->input('tables'); // Selected tables
            $permissions = $request->input('permissions'); // Selected permissions
            // dd($tables, $permissions);
            
            if (!$tables || !$permissions) {
                return back()->with('error', 'Please select at least one table and one permission.');
            }
            
            foreach ($tables as $table) {
                foreach ($permissions as $permission) {
                    $permissionName = ucfirst($permission) . ' ' . ucfirst($table);
            
                    Permission::firstOrCreate(
                        ['name' => $permissionName],
                        ['permissionto' => $table, 'created_at' => now(), 'updated_at' => now()] // Insert only if not exists
                    );
                }
            }
            
            return redirect()->route('roles.index')->with('success', 'Permissions added successfully!');
        }

    
        // public function edit(Role $role)
        public function edit(Request $request)
        {
            // $roles = Role::all(); // Fetch all roles for the dropdown
            // // $permissions = Permission::orderBy('permissionto')->paginate(25);
            // $permissions = Permission::all(); // Fetch all permissions for the checkboxes
        
            // return view('content.roles.edit', compact('role', 'roles', 'permissions'));

            $roles = Role::with('permissions')->get();

    // Get permissions grouped by 'permission_to'
            $groupedPermissions = Permission::orderBy('permissionto')
            ->orderBy('name') 
            ->get()
            ->groupBy('permissionto');

            // $paginatedPermissions = Permission::orderBy('permissionto')
            // ->orderBy('name')
            // ->paginate(10);;

    return view('content.roles.roles-layout', compact('roles', 'groupedPermissions'));
        }

        public function updatePermission(Request $request)
        {
            // dd($request->all());
            $roleMap = [
                'super_admin' => 'Super Admin',
                'admin' => 'Admin',
                'viewer' => 'Viewer',
            ];
        
            foreach ($roleMap as $formKey => $roleName) {
                $role = Role::where('name', $roleName)->first();
        
                if (!$role) continue; // Skip if role not found
        
                if ($request->has("permissions.$formKey")) {
                    $role->permissions()->sync($request->input("permissions.$formKey"));
                } else {
                    $role->permissions()->detach(); // No permissions checked
                }
            }
        
            return redirect()->route('roles.index')->with('success', 'Permissions updated successfully!');
        }
        
        public function update(Request $request, Role $role)
        {
            // Validate the request
            $validate = $request->validate([
                'name' => 'required|max:255|unique:roles,name,' . $role->id,
                'permissions' => 'array|exists:permissions,id',
            ]);
        
            // Update the role name
            $role->update(['name' => $request->name]);
        
            // Sync the permissions
            $role->permissions()->sync($request->permissions);
        
            return redirect()->route('roles.index')->with('success', 'Role updated successfully.');
        }
        
    
        public function destroy(Role $role)
        {
            // Delete the role
            $role->delete();
    
            return redirect()->route('roles.index')->with('success', 'Role deleted successfully.');
        }
        
    }
