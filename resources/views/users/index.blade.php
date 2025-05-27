@extends('layouts.app')

@section('title', 'Daftar Akun - SSO BPS')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">
        @if($canManageUsers)
            Kelola Akun
        @else
            Daftar Akun
        @endif
    </h4>
    @if($canManageUsers)
        <a href="{{ route('users.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Tambah Akun
        </a>
    @endif
</div>
@if(!$canManageUsers)
    <div class="alert alert-info">
        <i class="fas fa-info-circle"></i>
        <strong>Info:</strong> Anda dapat melihat daftar akun semua pengguna, namun hanya dapat mengedit profil diri sendiri. 
        Untuk melakukan aksi manajemen user lainnya, hubungi administrator.
    </div>
@endif
<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>NIP</th>
                        <th>Email</th>
                        <th>Tim</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                    <tr>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->nip9 }}<br>{{ $user->nip16 }}</td>
                        <td>{{ $user->email }}</td>
                        <td>
                            <div class="d-flex flex-wrap gap-1">
                                @foreach($user->roles as $role)
                                    <span class="role-tag">{{ ucfirst($role->name) }}</span>
                                @endforeach
                            </div>
                        </td>
                        <td>
                            @if($user->is_active)
                                <span class="badge bg-success">Aktif</span>
                            @else
                                <span class="badge bg-danger">Nonaktif</span>
                            @endif
                        </td>
                        <td>
                            @if($canManageUsers)
                                {{-- Admin dan umum bisa melakukan semua aksi --}}
                                <div class="d-flex gap-2">
                                    <a href="{{ route('users.edit', $user) }}" class="btn btn-sm btn-success" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    @if($user->id !== Auth::id())
                                        <form action="{{ route('users.toggle-status', $user) }}" method="POST" style="display: inline;">
                                            @csrf
                                            @method('PATCH')
                                            @if($user->is_active)
                                                <button type="submit" class="btn btn-sm btn-warning" title="Nonaktifkan">
                                                    <i class="fas fa-ban"></i>
                                                </button>
                                            @else
                                                <button type="submit" class="btn btn-sm btn-dark" title="Aktifkan">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                            @endif
                                        </form>
                                        @if(!$user->isAdmin() || Auth::user()->isAdmin())
                                            <form action="{{ route('users.destroy', $user) }}" method="POST" style="display: inline;"
                                                  onsubmit="return confirm('PERINGATAN: Ini akan menghapus akun {{ $user->name }} secara PERMANEN. Data yang sudah dihapus tidak dapat dikembalikan. Apakah Anda yakin?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" title="Hapus Permanen">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        @endif
                                    @endif
                                </div>
                            @else
                                {{-- Role lain hanya bisa edit diri sendiri --}}
                                @if($user->id === Auth::id())
                                    <a href="{{ route('users.edit', $user) }}" class="btn btn-sm btn-primary" title="Edit Profil Saya">
                                        <i class="fas fa-edit"></i> Edit Profil
                                    </a>
                                @else
                                    <span class="text-muted small">Tidak ada aksi</span>
                                @endif
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
