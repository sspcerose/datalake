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
    // dd('I am Here');
    return view('content.authentications.auth-forgot-password-basic');
  }

  public function handleResetRequest(Request $request)
    {
      try {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);
      } catch (\Illuminate\Validation\ValidationException $e) {
          // dd($e->errors()); 
          return back()->with('success', 'There is no user with this email.');
      }
        // dd('I am Here');
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
        // dd('I am Here');
        return back()->with('success', 'Password reset link sent to your email.');
    }

    public function showResetForm($token)
    {
      // dd('I am Here');
        return view('content.authentications.custom-reset', ['token' => $token]); // Create this view
    }

    public function resetPassword(Request $request)
    {
      // dd($request->all());
      // dd('I am Here');
        $validate = $request->validate([
            'email' => 'required|email|exists:users,email',
            'password' => 'required|min:8|confirmed',
            'token' => 'required',
        ]);
        // dd($validate);
        $resetRecord = DB::table('password_resets')
            ->where('email', $request->email)
            ->where('token', $request->token)
            ->first();
          // dd($resetRecord);
        if (!$resetRecord || Carbon::parse($resetRecord->created_at)->addMinutes(60)->isPast()) {
            return back()->withErrors(['error' => 'Token is invalid or expired.']);
            dd('I am Here');
        }
        // dd('I am...');
        $user = User::where('email', $request->email)->first();
        // dd($user);
        $user->update([
            'password' => Hash::make($request->password),
        ]);

        DB::table('password_resets')->where('email', $request->email)->delete();

        return redirect()->route('auth-login-basic')->with('success', 'Your password has been reset!');
    }
}
