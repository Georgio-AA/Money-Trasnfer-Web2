<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

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

        // Create user
        $user = User::create([
            'name' => $request->name,
            'surname' => $request->surname,
            'age' => $request->age,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Log the user in immediately after signup
        Session::put('user', [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
        ]);

        return redirect()->route('home')->with('success', 'Account created successfully!');
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
