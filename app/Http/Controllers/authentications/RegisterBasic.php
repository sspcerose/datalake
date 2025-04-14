<?php

namespace App\Http\Controllers\authentications;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class RegisterBasic extends Controller
{
  public function index()
  {
    return view('content.authentications.auth-register-basic');
  }

  public function register(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required',
            'password' => 'required',
        ]);

        
        User::create([
            'name' => $request->name,
            'role' => 'User',
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('auth-login-basic')->with('success', 'Registration successful! You can now log in.');
    }

  public function userRegisterForm()
  {
    return view('content.users.user-create');
  }

  public function userRegister(Request $request)
    {
      // dd('I am here');
      $request->validate([
        'username' => 'required',
        'first_name' => 'required',
        'last_name' => 'required',
        'email' => 'required|email:rfc,dns|unique:users,email',
        'password' => 'required|same:confirmpassword',
        'confirmpassword' => 'required',
        'user_type' => 'required',
    ]);
    // dd($request->all());

    DB::beginTransaction();
    try{
    // dd($request->all());

    $roleMap = [
      'Super Admin' => 1,
      'Admin' => 2,
      'Viewer' => 3,
    ];

    $role_id = $roleMap[$request->user_type] ?? null;
    // dd($role_id);

    DB::table('users')->insert([
        'username' => $request->username,
        'first_name' => $request->first_name,
        'last_name' => $request->last_name,
        'user_type' => $request->user_type,
        'email' => $request->email,
        'password' => Hash::make($request->password),
        'role_id' => $role_id,
    ]);
    DB::commit();
    
    $msg = ['success', 'User Record is Added!'];
    // dd('I was here');
    return redirect()->route('user-management')->with(['msg'=>$msg]);
  }catch(\Exception $e){
    DB::rollBack();
    // dd($e);
    $msg = ['danger', 'Failed to Add Record!'];
    return redirect()->back()->with(['msg'=>$msg]);
   }
}
}
