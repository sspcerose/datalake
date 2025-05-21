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
        // $sortField = $request->input('sort_field', 'track_name');
        // $sortOrder = $request->input('sort_order', 'asc');

        //     $query = DB::table('histories');
    
        //     if ($request->filled('filter')) {
        //         $filter = $request->input('filter');
        //         if (in_array($filter, ['created_at', 'updated_at'])) {
        //             $query->orderBy($filter, 'desc'); 
        //         } else {
        //             $query->where('artist_name', 'like', '%' . $request->filter . '%');
        //         }
               
        //     }
        //     if ($sortOrder !== 'none') {
        //         $query->orderBy($sortField, $sortOrder);
        //     }
            
        //     $samples = $query->paginate(5)->appends($request->except('page'));
        //     // $samples = $query->orderBy($sortField, $sortOrder)->paginate(5)->appends($request->except('page'));

        //     $jobsDone = DB::table('jobs_done')
        //     ->where('user_id', auth()->id())
        //     ->orderBy('created_at', 'desc')
        //     ->limit(5)
        //     ->get();
        $city_mun_code = DB::table('weather')
    ->select('city_mun_code')
    ->distinct()
    ->paginate(100); // Fetch 100 records per page

return view('content.table1.table1', compact('city_mun_code'));
        // return view('content.table1.table1', compact('samples', 'sortField', 'sortOrder', 'jobsDone'));    
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
        $query = DB::table('users')
                ->where('user_type', '!=', 'Super Admin');
                

        if ($request->filled('filter')) {
            $query->where('user_type', $request->filter);
        }

        if ($sortOrder !== 'none') {
            $query->orderBy($sortField, $sortOrder);
        }

        $users = $query->paginate(5)->appends($request->except('page'));

        $jobsDone = DB::table('jobs_done')
            ->where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // dd($users);

        return view('content.users.user-management', compact('users', 'sortField', 'sortOrder', 'jobsDone'));
    }
}
