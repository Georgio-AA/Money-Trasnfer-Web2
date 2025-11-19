<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use App\Mail\VerificationMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;



class AuthController extends Controller
{

    // google
    public function redirectToGoogle()
    {
    return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
{
    try {
        $googleUser = Socialite::driver('google')->user();

        // 1. Check if user already exists
        $user = User::where('email', $googleUser->getEmail())->first();

        if (!$user) {
            // 2. If not, create new user
            $user = User::create([
                'name' => $googleUser->getName(),
                'email' => $googleUser->getEmail(),
                'surname' => '',   // optional if your DB requires it
                'phone' => '',     // optional if required
                'age' => 0,        // optional if required
                'password' => Hash::make(Str::random(16)), // user doesn't need password
                'is_verified' => true, // Google users are already verified
            ]);
        }

        // 3. Store user session
        Session::put('user', [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
        ]);

        // 4. Redirect to home
        return redirect()->route('home')->with('success', 'Logged in with Google!');
        
    } catch (\Exception $e) {
        return redirect()->route('login')->with('error', 'Failed to log in with Google: ' . $e->getMessage());
    }
    }


    // public function showCompleteProfile()
    // {
    // return view('complete-profile');
    // }

    // public function saveCompleteProfile(Request $request)
    // {
    // $request->validate([
    //     'phone' => 'required|string|max:20|unique:users,phone',
    //     'age' => 'required|integer|min:1|max:120',
    // ]);

    // $user = User::find(Session::get('pending_user_id'));

    // $user->update([
    //     'phone' => $request->phone,
    //     'age' => $request->age,
    // ]);

    // Session::forget('pending_user_id');

    // // Login
    // Session::put('user', [
    //     'id' => $user->id,
    //     'name' => $user->name,
    //     'email' => $user->email,
    // ]);

    // return redirect()->route('home')->with('success', 'Profile completed!');
    // }




    public function showSignupForm()
    {
        // If user is already logged in, redirect to home
        if (Session::has('user')) {
            return redirect()->route('home');
        }
        return view('signup');
    }

    // -----------------------------
    // REGISTER NEW USER
    // -----------------------------
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'surname' => 'required|string|max:100',
            'age' => 'required|integer|min:1|max:120',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|string|max:20|unique:users,phone',
            'password' => [
                'required',
                'confirmed',             // must match password_confirmation
                'min:8',
                'regex:/[A-Z]/',         // at least one uppercase
                'regex:/[a-z]/',         // at least one lowercase
                'regex:/[0-9]/',         // at least one number
                'regex:/[@$!%*?&]/',     // at least one special char
            ],
        ], [
            'password.regex' => 'Password must contain upper, lower, number, and special character.',
        ]);

        $token = Str::random(64);

        // Create user
        $user = User::create([
            'name' => $request->name,
            'surname' => $request->surname,
            'age' => $request->age,
            'phone' => $request->phone,
            'email' => $request->email,
            'password' => Hash::make($request->password),
              'verification_token' => $token,
            'verification_due' => now()->addDay(),
        ]);

        // Log the user in immediately after signup
        Session::put('user', [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'phone' => $request->phone,

        ]);
        // Send verification email
        try {
            Mail::to($user->email)->send(new VerificationMail($user));
        } catch (\Throwable $e) {
            Log::error('Failed to send verification email: ' . $e->getMessage());
        }
        return redirect()->route('home')->with('name', $user->name)->with('success', 'Email verified successfully!');

        // return redirect()->route('home')->with('success', 'Account created successfully!');
    }

    // -----------------------------
    // SHOW LOGIN FORM
    // -----------------------------
    public function showLoginForm()
    {
        // If user is already logged in, redirect to home
        if (Session::has('user')) {
            return redirect()->route('home');
        }
        return view('login');
    }

    // -----------------------------
    // LOGIN USER
    // -----------------------------
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $request->email)->first();

        if ($user && Hash::check($request->password, $user->password)) {
            // Store user info in session
            Session::put('user', [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
            ]);

            return redirect()->route('home')->with('success', 'Welcome back, ' . $user->name . '!');
        }

        return back()->with('error', 'Invalid email or password.');
    }


    public function verifyEmail($token)
{
    $user = User::where('verification_token', $token)->first();

    if (!$user) {
        return redirect()->route('login')->with('error', 'Invalid verification link.');
    }

    if ($user->verification_due < now()) {
        return redirect()->route('login')->with('error', 'Verification link has expired.');
    }

    $user->update([
        'is_verified' => true,
        'verification_token' => null,
        'verification_due' => null,
    ]);

    return redirect()->route('login')->with('success', 'Email verified successfully! You can now log in.');
}


    public function logout()
    {
        Session::forget('user'); // removes the user session
        return redirect()->route('login')->with('success', 'You have logged out successfully.');
    }

    public function index()
    {
    if (!Session::has('user')) {
            return redirect()->route('login');
        }
        else{
            return view('index');
        }
    }


    public function services()
    {
        // Redirect to login if user is not logged in
        if (!Session::has('user')) {
            return redirect()->route('login');
        }
        else{
            return view('services');
        }
    }

    public function profile()
    {
        if (!Session::has('user')) {
            return redirect()->route('login');
        }
        
        $userId = Session::get('user.id');
        $user = User::find($userId);
        
        if (!$user) {
            return redirect()->route('login')->with('error', 'User not found.');
        }
        
        return view('profile', ['user' => $user]);
    }

    public function editProfile()
    {
        if (!Session::has('user')) {
            return redirect()->route('login');
        }
        
        $userId = Session::get('user.id');
        $user = User::find($userId);
        
        if (!$user) {
            return redirect()->route('login')->with('error', 'User not found.');
        }
        
        return view('edit-profile', ['user' => $user]);
    }

    public function updateProfile(Request $request)
    {
        if (!Session::has('user')) {
            return redirect()->route('login');
        }
        
        $userId = Session::get('user.id');
        $user = User::find($userId);
        
        if (!$user) {
            return redirect()->route('login')->with('error', 'User not found.');
        }
        
        $request->validate([
            'name' => 'required|string|max:100',
            'surname' => 'required|string|max:100',
            'age' => 'required|integer|min:1|max:120',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'required|string|max:20|unique:users,phone,' . $user->id,
        ]);

        $user->update([
            'name' => $request->name,
            'surname' => $request->surname,
            'age' => $request->age,
            'email' => $request->email,
            'phone' => $request->phone,
        ]);

        // Update session with new name
        Session::put('user.name', $request->name);

        return redirect()->route('profile')->with('success', 'Profile updated successfully!');
    }

    public function changePassword(Request $request)
    {
        // This method now sends a reset email instead of asking for current password
        // Redirect to request password reset
        return redirect()->route('password.request');
    }

    public function requestPasswordReset()
    {
        if (!Session::has('user')) {
            return redirect()->route('login');
        }
        
        return view('request-password-reset');
    }

    public function sendPasswordResetEmail(Request $request)
    {
        if (!Session::has('user')) {
            return redirect()->route('login');
        }
        
        $userId = Session::get('user.id');
        $user = User::find($userId);
        
        if (!$user) {
            return redirect()->route('login')->with('error', 'User not found.');
        }
        
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        // Generate reset token
        $resetToken = Str::random(64);
        $user->update([
            'password_reset_token' => $resetToken,
            'password_reset_due' => now()->addHour(),
        ]);

        // Send email with reset link
        try {
            Mail::to($user->email)->send(new \App\Mail\PasswordResetMail($user, $resetToken));
            return redirect()->route('profile')->with('success', 'Password reset link sent to your email!');
        } catch (\Throwable $e) {
            Log::error('Failed to send password reset email: ' . $e->getMessage());
            return back()->with('error', 'Failed to send reset email. Please try again.');
        }
    }

    public function showResetPasswordForm($token)
    {
        $user = User::where('password_reset_token', $token)->first();

        if (!$user) {
            return redirect()->route('login')->with('error', 'Invalid reset link.');
        }

        if ($user->password_reset_due < now()) {
            return redirect()->route('login')->with('error', 'Reset link has expired.');
        }

        return view('reset-password', ['token' => $token]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'new_password' => [
                'required',
                'confirmed',
                'min:8',
                'regex:/[A-Z]/',
                'regex:/[a-z]/',
                'regex:/[0-9]/',
                'regex:/[@$!%*?&]/',
            ],
        ], [
            'new_password.regex' => 'Password must contain upper, lower, number, and special character.',
        ]);

        $user = User::where('password_reset_token', $request->token)->first();

        if (!$user) {
            return redirect()->route('login')->with('error', 'Invalid reset link.');
        }

        if ($user->password_reset_due < now()) {
            return redirect()->route('login')->with('error', 'Reset link has expired.');
        }

        // Update password and clear reset token
        $user->update([
            'password' => Hash::make($request->new_password),
            'password_reset_token' => null,
            'password_reset_due' => null,
        ]);

        return redirect()->route('login')->with('success', 'Password reset successfully! You can now log in.');
    }

    // facebook
    public function redirectToFacebook()
{
    return Socialite::driver('facebook')->redirect();
}

public function handleFacebookCallback()
{
    try {
        $fbUser = Socialite::driver('facebook')->user();

        // Check if user exists
        $user = User::where('email', $fbUser->getEmail())->first();

        // If not, create user
        if (!$user) {
            $user = User::create([
                'name' => $fbUser->getName() ?? $fbUser->getNickname(),
                'email' => $fbUser->getEmail(),
                'password' => Hash::make(Str::random(16)), // random like Google
                'is_verified' => true,
            ]);
        }

        // If missing fields, redirect to complete profile
        if (!$user->age || !$user->phone) {
            Session::put('pending_user_id', $user->id);
            return redirect()->route('profile.complete');
        }

        // Store session
        Session::put('user', [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
        ]);

        return redirect()->route('home')->with('success', 'Logged in with Facebook!');
    } catch (\Exception $e) {
        return redirect()->route('login')->with('error', 'Failed to log in with Facebook: ' . $e->getMessage());
    }

    }



}
