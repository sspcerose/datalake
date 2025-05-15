<?php

namespace App\Http\Controllers\authentications;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\History;

class LoginBasic extends Controller
{
  public function index()
  { 
     if (Auth::check()) {
        return redirect()->route('dashboard-analytics'); 
    }

    return view('content.authentications.auth-login-basic');
  }

  public function login(Request $request)
  {
    // dd($request->all());
    $validate = $request->validate([
        'email' => 'required',
        'password' => 'required',
    ]);

    $user = User::where('email', $request->email)->first();

    if ($user && $user->status === 'inactive') {
      return back()->withErrors(['email' => 'You are not allowed to login.']);
    }
    if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
      $user = Auth::user(); 

      if ($user->password_changed !== 'true') {
        return redirect()->route('password.change')->withErrors([
            'password' => 'You must change your password before logging in.',
        ]);
    }
   
      return redirect()->route('weather.index')->with('user', $user);
  }
  // dd('I am here login');
    return back()->withErrors(['email' => 'Invalid credentials.']);
  // dd('Nothing');
}


  public function logout(Request $request)
  {
    Auth::logout();
    return redirect()->route('auth-login-basic');
  }

  public function showChangeForm()
  {
    return view('content.users.change-password');
  }

  public function update(Request $request)
{
    $request->validate([
        'current_password' => [
            'required',
            function ($attribute, $value, $fail) {
                if (!Hash::check($value, Auth::user()->password)) {
                    $fail('The current password is incorrect.');
                }
            }
        ],
        'new_password' => 'required|min:8|confirmed|different:current_password|same:new_password_confirmation',
        'new_password_confirmation' => 'required',
    ]);

    $user = Auth::user();
    $user->password = Hash::make($request->new_password);
    $user->password_changed = "true";
    $user->save();

    // Log out the user
    Auth::logout();

    // Redirect to the login page with a message
    return redirect()->route('auth-login-basic')->with('status', 'Password updated successfully! Please log in again.');
}
}
