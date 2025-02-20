<?php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view('session.register'); // Your view file is in session folder
    }

    public function sendOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $user = User::where('email', $request->email)->first();
        if ($user) {
            return response()->json(['error' => 'user_exists']);
        }

        $otp = rand(100000, 999999);
        Session::put('otp', $otp);
        Session::put('otp_email', $request->email);
        Session::put('otp_timestamp', now()->timestamp);

        // Send OTP via email
        Mail::raw("Your OTP for registration is: $otp", function ($message) use ($request) {
            $message->to($request->email)->subject('Your OTP Code');
        });

        return response()->json(['success' => true, 'otp' => $otp]);
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'otp' => 'required',
            'password' => 'required|min:6|confirmed',
        ]);

        if (Session::get('otp') != $request->otp || Session::get('otp_email') != $request->email) {
            return back()->withErrors(['otp' => 'Invalid OTP or expired.']);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        Session::forget(['otp', 'otp_email', 'otp_timestamp']);

        return redirect()->route('login')->with('success', 'Registration successful! Please log in.');
    }
}
