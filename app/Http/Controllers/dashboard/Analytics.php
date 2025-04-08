<?php

namespace App\Http\Controllers\dashboard;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Sample;
use App\Models\History;
use App\Models\User;

class Analytics extends Controller
{
    public function index(Request $request)
    {
        $sortField = $request->input('sort_field', 'track_name');
        $sortOrder = $request->input('sort_order', 'asc');
        // if (Auth::user()->user_type == 'Admin' || Auth::user()->user_type == 'User Type 1') {
            $query = DB::table('histories');
    
            if ($request->filled('filter')) {
                $filter = $request->input('filter');
                if (in_array($filter, ['created_at', 'updated_at'])) {
                    $query->orderBy($filter, 'desc'); 
                } else {
                    $query->where('artist_name', 'like', '%' . $request->filter . '%');
                }
               
            }

            if ($sortOrder !== 'none') {
                $query->orderBy($sortField, $sortOrder);
            }
            
            $samples = $query->paginate(5)->appends($request->except('page'));
            // $samples = $query->orderBy($sortField, $sortOrder)->paginate(5)->appends($request->except('page'));

            return view('content.table1.table1', compact('samples', 'sortField', 'sortOrder'));
    }

    public function table2()
    {
        return view ('content.dashboard.table2');
    }

public function userManagement(Request $request)
{
    $sortField = $request->input('sort_field', 'first_name');
    $sortOrder = $request->input('sort_order', 'asc');

    // if (Auth::user()->user_type == 'Admin' || Auth::user()->user_type == 'User Type 1') {
        $query = DB::table('users');

    if ($request->filled('filter')) {
        $query->where('user_type', 'like', '%' . $request->filter . '%');
    }

    $query->where('user_type', '!=', 'Admin');

    if ($sortOrder !== 'none') {
        $query->orderBy($sortField, $sortOrder);
    }

    $users = $query->paginate(5)->appends($request->except('page'));

    return view('content.users.user-management', compact('users', 'sortField', 'sortOrder'));
    }
}
