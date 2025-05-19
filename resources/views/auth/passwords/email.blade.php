@extends('layouts.auth')

@section('content')
<div class="login-container">
    <div class="left-side">
        <div class="logo">
            <img src="{{ asset('Logo_tagline.png') }}" alt="BPS Logo">
        </div>
        <h1 class="title">SINGLE SIGN-ON BPS</h1>
        <p class="subtitle">Reset your password<br>to recover access</p>
    </div>
    <div class="right-side">
        <div class="sign-in-header">
            <h2 class="sign-in-title">RESET PASSWORD</h2>
            <p class="sign-in-subtitle">RECOVER YOUR ACCOUNT</p>
        </div>
        
        @if (session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
        @endif
        
        <form method="POST" action="{{ route('password.email') }}">
            @csrf
            <div class="form-group">
                <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" placeholder="Email" required>
                @error('email')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>
            
            <button type="submit" class="btn-login">Send Password Reset Link</button>
            
            <div class="text-center mt-3">
                <p>Remember your password? <a href="{{ route('login') }}">Login</a></p>
            </div>
        </form>
    </div>
</div>
@endsection
