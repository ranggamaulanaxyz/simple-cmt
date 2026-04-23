@extends('layouts.app')
@section('title', 'Manajemen User')
@section('page-title', 'Manajemen User')

@section('content')
    <div class="bg-white rounded-xl p-8" style="box-shadow: 0px 1px 3px rgba(45,27,77,0.06);">
        <div class="flex justify-between items-center mb-8">
            <div>
                <h2 class="text-xl font-bold text-on-surface">Daftar User</h2>
                <p class="text-sm text-on-surface/40 mt-1">Kelola semua pengguna sistem</p>
            </div>
            <a href="{{ route('users.create') }}" class="py-2.5 px-5 bg-primary hover:bg-primary-dark text-white rounded-md transition-all font-bold flex items-center gap-2 active:scale-[0.98] text-xs uppercase tracking-widest">
                <span class="material-symbols-outlined text-sm">person_add</span>
                Tambah User
            </a>
        </div>

        {{-- Filters --}}
        <form method="GET" action="{{ route('users.index') }}" class="flex flex-wrap gap-3 mb-6">
            <div class="relative flex-1 min-w-[200px]">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface/30 text-lg">search</span>
                <input name="search" value="{{ request('search') }}" type="text" class="w-full bg-surface-container-low rounded-md py-2.5 pl-10 pr-4 text-sm border-none" placeholder="Cari nama atau email...">
            </div>
            <select name="role" class="bg-surface-container-low rounded-md py-2.5 px-4 text-sm border-none min-w-[140px]">
                <option value="">Semua Role</option>
                <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                <option value="pimpinan" {{ request('role') === 'pimpinan' ? 'selected' : '' }}>Pimpinan</option>
                <option value="pegawai" {{ request('role') === 'pegawai' ? 'selected' : '' }}>Pegawai</option>
            </select>
            <select name="status" class="bg-surface-container-low rounded-md py-2.5 px-4 text-sm border-none min-w-[140px]">
                <option value="">Semua Status</option>
                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Aktif</option>
                <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Nonaktif</option>
            </select>
            <button type="submit" class="py-2.5 px-5 bg-surface-container-high hover:bg-surface-container-highest text-on-surface rounded-md font-bold text-xs uppercase tracking-widest transition-all">
                Filter
            </button>
        </form>

        {{-- Table --}}
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="text-[10px] font-bold text-on-surface/40 uppercase tracking-widest" style="border-bottom: 1px solid rgba(203,196,207,0.15);">
                        <th class="pb-4">Nama</th>
                        <th class="pb-4">Email</th>
                        <th class="pb-4">Role</th>
                        <th class="pb-4">Status</th>
                        <th class="pb-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-sm">
                    @forelse($users as $user)
                        <tr class="table-row-hover" style="border-bottom: 1px solid rgba(203,196,207,0.08);">
                            <td class="py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-md bg-primary/10 flex items-center justify-center text-[10px] font-bold text-primary">{{ $user->initials }}</div>
                                    <span class="font-semibold text-on-surface">{{ $user->name }}</span>
                                </div>
                            </td>
                            <td class="py-4 text-on-surface/60">{{ $user->email }}</td>
                            <td class="py-4"><x-role-badge :role="$user->role" /></td>
                            <td class="py-4"><x-active-badge :status="$user->is_active ? 'active' : 'inactive'" /></td>
                            <td class="py-4">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('users.edit', $user) }}" class="p-1.5 hover:bg-surface-container rounded transition-colors" title="Edit">
                                        <span class="material-symbols-outlined text-base text-on-surface-variant">edit</span>
                                    </a>
                                    <form method="POST" action="{{ route('users.toggle-active', $user) }}" class="inline">
                                        @csrf
                                        <button type="submit" class="p-1.5 hover:bg-surface-container rounded transition-colors" title="{{ $user->is_active ? 'Nonaktifkan' : 'Aktifkan' }}">
                                            <span class="material-symbols-outlined text-base {{ $user->is_active ? 'text-warning' : 'text-success' }}">
                                                {{ $user->is_active ? 'block' : 'check_circle' }}
                                            </span>
                                        </button>
                                    </form>
                                    <form method="POST" action="{{ route('users.destroy', $user) }}" class="inline" onsubmit="return confirm('Yakin ingin menghapus user ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1.5 hover:bg-error-bg rounded transition-colors" title="Hapus">
                                            <span class="material-symbols-outlined text-base text-error">delete</span>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-12 text-center text-on-surface/30">
                                <span class="material-symbols-outlined text-4xl mb-2 block">person_off</span>
                                <p class="text-sm font-medium">Tidak ada user ditemukan</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-6">{{ $users->links() }}</div>
    </div>
@endsection