<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class checkPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    //////////////////// PERMISSION MIDDLEWARE 1 /////////////////////////////////////////////
    // public function handle(Request $request, Closure $next): Response
    // {
    //     $user = Auth::user();
    //     $action = $request->route()->getActionMethod();
    //     $controller = class_basename($request->route()->getController());

    //     // Define the permissions
    //     $permissions = [
    //         'Super Admin' => [
    //             'Table1Controller' => ['index', 'create', 'store', 'edit', 'update', 'destroy', 'show', 'search'],
    //             'ImportController' => ['importUsers', 'process', 'getProgress', 'insertBatch', 'importTable1', 'weatherProcess'],
    //             'ExportController' => ['export', 'download', 'exportUsers', 'exportTable1', 'exportCsv', 'exportUsers', 'exportWeather'],
    //             'Analytics' => ['index', 'table2', 'userManagement'],
    //             'UserController' => ['index', 'create', 'store', 'edit', 'update', 'destroy', 'show', 'userSearch'],
    //             'RegisterBasic' => ['userRegisterForm', 'userRegister'],
    //             'WeatherController' => ['index', 'create', 'store', 'edit', 'update', 'destroy', 'show'],
    //         ],
    //         'Admin' => [
    //             'Table1Controller' => ['index', 'create', 'store', 'edit', 'update', 'destroy', 'show', 'search'],
    //             'ImportController' => ['importUsers', 'process', 'getProgress', 'insertBatch', 'importTable1', 'weatherProcess'],
    //             'ExportController' => ['export', 'download', 'exportUsers', 'exportTable1', 'exportCsv', 'exportUsers', 'exportWeather'],
    //             'Analytics' => ['index', 'table2', 'userManagement'],
    //             'UserController' => ['index', 'create', 'store', 'show', 'userSearch'],
    //             'RegisterBasic' => ['userRegisterForm', 'userRegister'],
    //             'WeatherController' => ['index', 'create', 'store', 'edit', 'update', 'destroy', 'show'],
    //         ],
    //         'Viewer' => [
    //             'Table1Controller' => ['index', 'show'],
    //             'Analytics' => ['index', 'table2'],
    //             'WeatherController' => ['index'],
    //         ],
            
    //     ];

    //     if (!isset($permissions[$user->user_type]) || !isset($permissions[$user->user_type][$controller])) {
    //         return redirect()->back()->with('error', 'Unauthorized access.');
    //     }

    //     // Check if the action is allowed for the user's role in that controller
    //     if (!in_array($action, $permissions[$user->user_type][$controller])) {
    //         return redirect()->back()->with('error', 'Unauthorized access.');
    //     }

    //     return $next($request);
    // }

    /////////////////////////PERMISSION MIDDLEWARE 2 ///////////////////////////

    public function handle($request, Closure $next, $permission)
    {
        if (!auth()->user() || !auth()->user()->hasPermission($permission)) {
            // abort(403, 'Unauthorized action.');
            return back();
        }

        return $next($request);
    }
}
