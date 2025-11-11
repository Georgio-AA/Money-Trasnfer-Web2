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


class AuthController extends Controller
{
    // -----------------------------
    // SHOW SIGNUP FORM
    // -----------------------------
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
}
