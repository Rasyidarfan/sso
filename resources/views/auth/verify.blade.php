@extends('layouts.auth')

@section('content')
<div class="login-container">
    <div class="left-side">
        <div class="logo">
            <img src="{{ asset('Logo_tagline.png') }}" alt="BPS Logo">
        </div>
        <h1 class="title">SINGLE SIGN-ON BPS</h1>
        <p class="subtitle">Verify your email<br>to complete registration</p>
    </div>
    <div class="right-side">
        <div class="sign-in-header">
            <h2 class="sign-in-title">VERIFY EMAIL</h2>
            <p class="sign-in-subtitle">COMPLETE YOUR REGISTRATION</p>
        </div>
        
        @if (session('resent'))
            <div class="alert alert-success" role="alert">
                A fresh verification link has been sent to your email address.
            </div>
        @endif

        <p class="mb-4">Before proceeding, please check your email for a verification link. If you did not receive the email, click the button below to request another.</p>
        
        <form method="POST" action="{{ route('verification.resend') }}">
            @csrf
            <button type="submit" class="btn-login">Resend Verification Email</button>
        </form>
    </div>
</div>
@endsection
