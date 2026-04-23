@extends('layouts.app')
@section('title', 'Tambah User')
@section('page-title', 'Tambah User')

@section('content')
<div class="max-w-2xl">
    <div class="bg-white rounded-xl p-8" style="box-shadow: 0px 1px 3px rgba(45,27,77,0.06);">
        <h2 class="text-xl font-bold text-on-surface mb-6">Tambah User Baru</h2>

        <form method="POST" action="{{ route('users.store') }}" class="space-y-6">
            @csrf

            <div>
                <label for="name" class="block text-[10px] font-bold uppercase tracking-[0.12em] text-on-surface/50 mb-2">Nama</label>
                <input id="name" name="name" type="text" value="{{ old('name') }}" required
                    class="w-full bg-surface-container-low rounded-md py-3 px-4 text-sm border-none @error('name') ring-2 ring-error/30 @enderror">
                @error('name') <p class="text-xs text-error mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="email" class="block text-[10px] font-bold uppercase tracking-[0.12em] text-on-surface/50 mb-2">Email</label>
                <input id="email" name="email" type="email" value="{{ old('email') }}" required
                    class="w-full bg-surface-container-low rounded-md py-3 px-4 text-sm border-none @error('email') ring-2 ring-error/30 @enderror">
                @error('email') <p class="text-xs text-error mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="role" class="block text-[10px] font-bold uppercase tracking-[0.12em] text-on-surface/50 mb-2">Role</label>
                <select id="role" name="role" required
                    class="w-full bg-surface-container-low rounded-md py-3 px-4 text-sm border-none">
                    <option value="">Pilih Role</option>
                    <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="pimpinan" {{ old('role') === 'pimpinan' ? 'selected' : '' }}>Pimpinan</option>
                    <option value="pegawai" {{ old('role') === 'pegawai' ? 'selected' : '' }}>Pegawai</option>
                </select>
                @error('role') <p class="text-xs text-error mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="password" class="block text-[10px] font-bold uppercase tracking-[0.12em] text-on-surface/50 mb-2">Password</label>
                <input id="password" name="password" type="password" required
                    class="w-full bg-surface-container-low rounded-md py-3 px-4 text-sm border-none @error('password') ring-2 ring-error/30 @enderror">
                @error('password') <p class="text-xs text-error mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="password_confirmation" class="block text-[10px] font-bold uppercase tracking-[0.12em] text-on-surface/50 mb-2">Konfirmasi Password</label>
                <input id="password_confirmation" name="password_confirmation" type="password" required
                    class="w-full bg-surface-container-low rounded-md py-3 px-4 text-sm border-none">
            </div>

            <div class="flex items-center gap-3 pt-4">
                <button type="submit"
                    class="py-3 px-6 bg-primary hover:bg-primary-dark text-white rounded-md transition-all font-bold text-sm active:scale-[0.98]">
                    Simpan
                </button>
                <a href="{{ route('users.index') }}" class="py-3 px-6 text-on-surface-variant hover:text-on-surface font-bold text-sm transition-colors">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
