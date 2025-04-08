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
    return view('content.authentications.auth-login-basic');
  }

  public function login(Request $request)
  {
  // dd($request->all());
    $validate = $request->validate([
        'email' => 'required',
        'password' => 'required',
    ]);
    // dd($validate);
    $user = User::where('email', $request->email)->first();
    // dd($user);
    if ($user && $user->status === 'inactive') {
      return back()->withErrors(['email' => 'You are not allowed to login.']);
    }
    if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
      $user = Auth::user(); 
      // dd($user);
      if ($user->password_changed !== 'true') {
        // Auth::logout(); 
        return redirect()->route('password.change')->withErrors([
            'password' => 'You must change your password before logging in.',
        ]);
    }
      return redirect()->route('dashboard-analytics')->with('user', $user);
  }
    return back()->withErrors(['email' => 'Invalid credentials.']);
}

// public function login(Request $request)
// {
//      $validate = $request->validate([
//         'email' => 'required',
//         'password' => 'required',
//     ]);

//     $user = User::where('email', $request->email)->first();

//     if ($user && $user->status === 'inactive') {
//         return response()->json([
//             'success' => false,
//             'message' => 'You are not allowed to login.',
//         ], 403);
//     }

//     if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
//         $user = Auth::user();

//         if ($user->password_changed !== 'true') {
//             return response()->json([
//                 'success' => false,
//                 'message' => 'You must change your password before logging in.',
//                 'redirect_url' => route('password.change'),
//             ], 403);
//         }

//         return response()->json([
//             'success' => true,
//             'redirect_url' => route('dashboard-analytics'),
//         ], 200);
//     }

//     return response()->json([
//         'success' => false,
//         'message' => 'Invalid credentials.',
//     ], 401);
// }

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
      // dd($request->all());
        $request->validate([
            'current_password' => ['required', function ($attribute, $value, $fail) {
            if (!Hash::check($value, Auth::user()->password)) {
                $fail('The current password is incorrect.');
            }
        }],
            'new_password' => 'required|min:8|confirmed|different:current_password|same:new_password_confirmation',
            'new_password_confirmation' => 'required',
        ]);

        $user = Auth::user();
        $user->password = Hash::make($request->new_password);
        $user->password_changed = "true";
        $user->save();

        return redirect()->route('auth-login-basic')->with('status', 'Password updated successfully!');
    }
}
