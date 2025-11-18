<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SwiftPay - Money Transfer</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>
<header>
    <div class="navbar">
        <div class="logo">SwiftPay</div>
        <nav>
            <ul>
                <li><a href="{{ route('home') }}">Home</a></li>
                <li><a href="{{ route('services') }}">Services</a></li>
                <li><a href="{{ route('agents') }}">Agents</a></li>
                @if(session()->has('user'))
                    <li><a href="{{ route('transfers.create') }}" class="send-money-link">Send Money</a></li>
                    <li><a href="{{ route('transfers.index') }}">My Transfers</a></li>
                    <li><a href="{{ route('beneficiaries.index') }}">Beneficiaries</a></li>
                    <li><a href="{{ route('bank-accounts.index') }}">My Accounts</a></li>
                    <li><a href="{{ route('transfer-services.index') }}">Transfer Services</a></li>
                @else
                    <li><a href="{{ route('login') }}">Send Money</a></li>
                @endif

                @if(session()->has('user'))
                    {{-- User is logged in --}}
                    <li>Welcome, {{ session('user.name') }}</li>
                    <li>
                        <form action="{{ route('logout') }}" method="POST" style="display:inline;">
                            @csrf
                            <button type="submit" class="logout-btn">Logout</button>
                        </form>
                    </li>
                @else
                    {{-- User is not logged in --}}
                    <li><a href="{{ route('login') }}">Login</a></li>
                    <li><a href="{{ route('signup') }}" class="signup-btn">Sign Up</a></li>
                @endif
            </ul>
        </nav>
    </div>
</header>
<main>
