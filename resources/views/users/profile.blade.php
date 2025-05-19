@extends('layouts.app')

@section('title', 'Profile - SSO BPS')

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="card mb-4">
            <div class="card-body">
                <div class="d-flex align-items-center mb-4">
                    <div>
                        <h4 class="mb-1">{{ $user->name }}</h4>
                        <div class="text-muted">{{ $user->email }}</div>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label text-muted">NIP (9 Digit)</label>
                            <div class="fw-bold">{{ $user->nip9 }}</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label text-muted">NIP (18 Digit)</label>
                            <div class="fw-bold">{{ $user->nip16 }}</div>
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label text-muted">Tim</label>
                    <div>
                        @foreach($user->roles as $role)
                            <span class="role-tag">{{ ucfirst($role->name) }}</span>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">Ubah Password</h4>

                <form method="POST" action="{{ route('profile.password') }}">
                    @csrf

                    <div class="mb-3">
                        <label for="current_password" class="form-label">Password Lama</label>
                        <input type="password" class="form-control @error('current_password') is-invalid @enderror" id="current_password" name="current_password" required>
                        @error('current_password')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Password Baru</label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" required>
                        @error('password')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">Konfirmasi Password Baru</label>
                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">Update Password</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
