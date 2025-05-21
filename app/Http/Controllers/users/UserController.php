<?php

namespace App\Http\Controllers\users;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // $user = User::find($id);
        // return view('content.users.user-show', compact('user'));
        $user = User::findOrFail($id);
        return response()->json($user);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // $user = User::find($id);
        // return view('content.users.user-update', compact('user'));
        $user = User::findOrFail($id);
        return response()->json($user);
        
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // dd($request->all());
        $validateData = $request->validate([
            'user_type' => 'required',
            'status' => 'required',
           ]);

           DB::beginTransaction();
           try{
            DB::table('users')
            ->where('id', $id)
            ->update([
                'username' => $request->username,
                'first_name' => $request->first_name,
                'first_name' => $request->last_name,
                'email' => $request->email,
                'user_type' => $request->user_type,
                'password' => Hash::make($request->password),
                'status' => $request->status,
            ]);
            DB::commit();
    
            $msg = ['success', 'User Record is Updated!'];
            return redirect()->route('user-management')->with(['msg'=>$msg]);
           }catch(\Exception $e){
            DB::rollBack();
            $msg = ['danger', 'Failed to Update Record!'];
            return redirect()->back()->with(['msg'=>$msg]);
           }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        DB::table('users')->where('id', $id)->delete();
        $msg = ['danger', 'Record is Deleted!'];
        return redirect()->route('user-management')->with('success', 'User Record is Deleted!' );
    }

    public function userProfile(Request $request) 
    {
        return view('content.users.profile', ['user' => $request->user(),]);
    }

    public function updateProfile(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'email' => 'required|email|unique:users,email,' . $request->user()->id,
            'first_name' => 'required',
            'last_name' => 'required',
        ]);

        $user = $request->user();

        // if ($request->has('password') && !empty($request->password)) {
        //     $user->password = bcrypt($request->password);
        // }

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
            
        }
        if ($request->has('email') && $user->email !== $request->email) {
            $user->email = $request->email;
        }

        if ($request->has('username') && $user->username !== $request->username) {
            $user->username = $request->username;
        }
        if ($request->has('first_name') && $user->first_name !== $request->first_name) {
            $user->first_name = $request->first_name;
        }
        if ($request->has('last_name') && $user->last_name !== $request->last_name) {
            $user->last_name = $request->last_name;
        }

        if ($request->has('user_type') && $user->user_type !== $request->user_type) {
            $user->user_type = $request->user_type;
        }

        // if ($request->has('status') && $user->status !== $request->status) {
        //     $user->status = $request->status;
        // }

        $user->save();

        return redirect()->route('user-profile')->with('status', 'profile-updated');
    }

    public function changePassword(){
        dd('change password');
    }

    public function userSearch(Request $request)
    {
        $query = $request->input('query');

        if (!$query) {
            return response()->json([], 200);
        }

        DB::beginTransaction(); 
        try {
            $users = DB::table('users')
                ->where('user_type', '!=', 'Super Admin')
                ->where(function ($queryBuilder) use ($query) {
                    $queryBuilder->where('first_name', 'like', "%$query%")
                        ->orWhere('last_name', 'like', "%$query%")
                        ->orWhere('email', 'like', "%$query%")
                        ->orWhere('user_type', 'like', "%$query%")
                        ->orWhere('status', $query);
                })
                ->paginate(5);
        
            DB::commit(); 
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors(['error' => 'Something went wrong. Please try again.']);
        }
            return response()->json([
                'data' => $users->items(),
                'links' => $users->links('vendor.pagination.bootstrap-5')->render()
            ]);
    }
}
