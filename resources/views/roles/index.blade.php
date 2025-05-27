@extends('layouts.app')

@section('title', 'Daftar Tim/Role - SSO BPS')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">Daftar Tim/Role</h4>
    <a href="{{ route('roles.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> Tambah Role
    </a>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Nama Role</th>
                        <th>Deskripsi</th>
                        <th>Jumlah User</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($roles as $role)
                    <tr>
                        <td>
                            <span class="role-tag">{{ ucfirst($role->name) }}</span>
                        </td>
                        <td>{{ $role->description ?? '-' }}</td>
                        <td>
                            <a href="{{ route('roles.show', $role) }}" class="text-decoration-none">
                                {{ $role->users_count }} user
                            </a>
                        </td>
                        <td>
                            <div class="d-flex gap-2">
                                <a href="{{ route('roles.show', $role) }}" class="btn btn-sm btn-info">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('roles.edit', $role) }}" class="btn btn-sm btn-success">
                                    <i class="fas fa-edit"></i>
                                </a>
                                @if(strtolower($role->name) !== 'admin')
                                    <form action="{{ route('roles.destroy', $role) }}" method="POST" 
                                          onsubmit="return confirm('Apakah Anda yakin ingin menghapus role {{ $role->name }}?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger"
                                                {{ $role->users_count > 0 ? 'disabled' : '' }}>
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                @else
                                    <button class="btn btn-sm btn-secondary" disabled title="Role admin tidak dapat dihapus">
                                        <i class="fas fa-lock"></i>
                                    </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
