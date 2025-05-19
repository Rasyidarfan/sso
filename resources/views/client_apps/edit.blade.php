@extends('layouts.app')

@section('title', 'Edit Aplikasi - SSO BPS')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">Edit Aplikasi: {{ $clientApp->name }}</h4>
                
                <form method="POST" action="{{ route('client-apps.update', $clientApp) }}">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label for="name" class="form-label">Nama Aplikasi</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $clientApp->name) }}" required>
                        <small class="text-muted">Nama aplikasi yang akan menggunakan SSO</small>
                        @error('name')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="redirect_uri" class="form-label">Redirect URI</label>
                        <input type="url" class="form-control @error('redirect_uri') is-invalid @enderror" id="redirect_uri" name="redirect_uri" value="{{ old('redirect_uri', $clientApp->redirect_uri) }}" required>
                        <small class="text-muted">URL callback setelah user berhasil login</small>
                        @error('redirect_uri')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="description" class="form-label">Deskripsi</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3">{{ old('description', $clientApp->description) }}</textarea>
                        <small class="text-muted">Deskripsi singkat tentang aplikasi</small>
                        @error('description')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    
                    <div class="mt-4 d-flex justify-content-between">
                        <a href="{{ route('client-apps.index') }}" class="btn btn-secondary">Kembali</a>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
