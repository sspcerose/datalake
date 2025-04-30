<?php

namespace App\Http\Controllers\authentications;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use App\Models\User;

class ForgotPasswordBasic extends Controller
{
  public function index()
  {
    return view('content.authentications.auth-forgot-password-basic');
  }

  public function handleResetRequest(Request $request)
  {
    // Validates the email input [exists in the users table or not]
    try {
      $request->validate([
          'email' => 'required|email|exists:users,email',
      ]);
    } catch (\Illuminate\Validation\ValidationException $e) {
      return back()->with('success', 'There is no user with this email.');
    }
    // Generates a random token and stores it in the password_resets table with the email
      $token = \Str::random(64);

      DB::table('password_resets')->updateOrInsert(
          ['email' => $request->email],
          ['token' => $token, 'created_at' => Carbon::now()]
      );

      $link = url('password/reset/' . $token);

      Mail::send('content.authentications.password-reset', ['link' => $link], function ($message) use ($request) {
          $message->to($request->email)
                  ->subject('Password Reset Request');
      });
      
      return back()->with('success', 'Password reset link sent to your email.');

    }

  public function showResetForm($token)
  {
    return view('content.authentications.custom-reset', ['token' => $token]); 
  }

  public function resetPassword(Request $request)
  {
    $validate = $request->validate([
      'email' => 'required|email|exists:users,email',
      'password' => 'required|min:8|confirmed',
      'token' => 'required',
    ]);
    $resetRecord = DB::table('password_resets')
      ->where('email', $request->email)
      ->where('token', $request->token)
      ->first();
      
    // Validates the token and checks if it is expired (60 minutes)
    if(!$resetRecord || Carbon::parse($resetRecord->created_at)->addMinutes(60)->isPast()) {
      return back()->withErrors(['error' => 'Token is invalid or expired.']);
    }
    $user = User::where('email', $request->email)->first();
    $user->update([
      'password' => Hash::make($request->password),
    ]);

    DB::table('password_resets')->where('email', $request->email)->delete();

    return redirect()->route('auth-login-basic')->with('success', 'Your password has been reset!');
    }

}
