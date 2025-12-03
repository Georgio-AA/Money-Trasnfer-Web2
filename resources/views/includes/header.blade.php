<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SwiftPay - Money Transfer</title>
    @vite('assets/css/style.css')
</head>
<body>
<header>
    <div class="navbar">
        <div class="logo">SwiftPay</div>
        <nav>
            <ul>
                <li><a href="{{ route('home') }}">Home</a></li>
                <li><a href="{{ route('services') }}">Services</a></li>
                 @if(session('user.role') !== 'agent')
                <li><a href="{{ route('agent.applytobeagent') }}">Become an Agent</a></li>
                @endif
                @if(session('user.role') === 'agent')
                <li><a href="{{ route('agent.dashboard') }}">Agent Dashboard</a></li>
                <li><a href="{{ route('agent.profile.edit') }}">Agent Profile</a></li>
                @endif

                @if(session()->has('user'))
                    <li><a href="{{ route('wallet.index') }}" class="wallet-link">💰 My Wallet</a></li>
                    <li><a href="{{ route('transfers.create') }}" class="send-money-link">Send Money</a></li>
                    <li><a href="{{ route('transfers.index') }}">My Transfers</a></li>
                    <li><a href="{{ route('beneficiaries.index') }}">Beneficiaries</a></li>
                    <li><a href="{{ route('bank-accounts.index') }}">My Accounts</a></li>
                    <li><a href="{{ route('support.index') }}">Support</a></li>
                    <li><a href="{{ route('reviews.index') }}">Reviews</a></li>
                    <li><a href="{{ route('disputes.index') }}">Disputes</a></li>
                    @if(session('user.role') === 'admin')
                        <li><a href="{{ route('admin.dashboard') }}" style="color: #f59e0b; font-weight: 600;">Admin Panel</a></li>
                    @endif
                    <li><a href="{{ route('transfer-services.index') }}">Transfer Services</a></li>
                @else
                    <li><a href="{{ route('login') }}">Send Money</a></li>
                @endif

                @if(session()->has('user'))
                    {{-- User is logged in --}}
                    @if(session('user.is_admin'))
                        <li><a href="{{ route('admin.dashboard') }}">Admin</a></li>
                    @endif
                    <li><a href="{{ route('profile') }}" style="color: inherit; text-decoration: none;">Welcome, {{ session('user.name') }}</a></li>
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
