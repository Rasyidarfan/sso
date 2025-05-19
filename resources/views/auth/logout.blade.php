@extends('layouts.auth')

@section('content')
<div class="login-container">
    <div class="left-side">
        <div class="logo">
            <img src="{{ asset('Logo_tagline.png') }}" alt="BPS Logo">
        </div>
        <h1 class="title">SINGLE SIGN-ON BPS</h1>
        <p class="subtitle">You have been<br>successfully logged out</p>
    </div>
    <div class="right-side">
        <div class="sign-in-header">
            <h2 class="sign-in-title">LOGGED OUT</h2>
            <p class="sign-in-subtitle">SESSION ENDED</p>
        </div>
        
        <div class="text-center mb-4">
            <p>You have been successfully logged out of your account.</p>
            <p>Thank you for using SSO BPS.</p>
        </div>
        
        <a href="{{ route('login') }}" class="btn-login d-block text-center text-decoration-none">Log In Again</a>
    </div>
</div>
@endsection
