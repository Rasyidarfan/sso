@extends('layouts.app')

@section('title', 'Edit Akun - SSO BPS')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">Edit Akun: {{ $user->name }}</h4>
                
                <form method="POST" action="{{ route('users.update', $user) }}">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label for="name" class="form-label">Nama Lengkap</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $user->name) }}" required>
                        @error('name')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $user->email) }}" required>
                        @error('email')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="nip9" class="form-label">NIP 9 Digit</label>
                            <input type="text" class="form-control @error('nip9') is-invalid @enderror" id="nip9" name="nip9" value="{{ old('nip9', $user->nip9) }}" required>
                            @error('nip9')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="nip16" class="form-label">NIP 18 Digit</label>
                            <input type="text" class="form-control @error('nip16') is-invalid @enderror" id="nip16" name="nip16" value="{{ old('nip16', $user->nip16) }}" required>
                            @error('nip16')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Tim</label>
                        <div class="row">
                            @foreach($roles as $role)
                                <div class="col-md-4 mb-2">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="role_{{ $role->id }}" name="roles[]" value="{{ $role->id }}" {{ in_array($role->id, old('roles', $userRoleIds)) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="role_{{ $role->id }}">{{ ucfirst($role->name) }}</label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        @error('roles')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    
                    <div class="mt-4 d-flex justify-content-between">
                        <a href="{{ route('users.index') }}" class="btn btn-secondary">Kembali</a>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
