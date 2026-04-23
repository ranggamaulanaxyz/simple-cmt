@extends('layouts.app')
@section('title', 'Profil')
@section('page-title', 'Profil Saya')

@section('content')
<div class="max-w-2xl space-y-8">

    {{-- Profile Info --}}
    <div class="bg-white rounded-xl p-8" style="box-shadow: 0px 1px 3px rgba(45,27,77,0.06);">
        <h2 class="text-xl font-bold text-on-surface mb-6">Informasi Profil</h2>

        <form method="POST" action="{{ route('profile.update') }}" class="space-y-6">
            @csrf
            @method('PUT')

            <div>
                <label for="profile-name" class="block text-[10px] font-bold uppercase tracking-[0.12em] text-on-surface/50 mb-2">Nama</label>
                <input id="profile-name" name="name" type="text" value="{{ old('name', $user->name) }}" required
                    class="w-full bg-surface-container-low rounded-md py-3 px-4 text-sm border-none @error('name') ring-2 ring-error/30 @enderror">
                @error('name') <p class="text-xs text-error mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="profile-email" class="block text-[10px] font-bold uppercase tracking-[0.12em] text-on-surface/50 mb-2">Email</label>
                <input id="profile-email" name="email" type="email" value="{{ old('email', $user->email) }}" required
                    class="w-full bg-surface-container-low rounded-md py-3 px-4 text-sm border-none @error('email') ring-2 ring-error/30 @enderror">
                @error('email') <p class="text-xs text-error mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-[10px] font-bold uppercase tracking-[0.12em] text-on-surface/50 mb-2">Role</label>
                <div class="py-3 px-4 bg-surface-container-low rounded-md text-sm text-on-surface/60">
                    {{ ucfirst($user->role) }}
                    <span class="text-[10px] text-on-surface/30 ml-2">(tidak dapat diubah)</span>
                </div>
            </div>

            <button type="submit" class="py-3 px-6 bg-primary hover:bg-primary-dark text-white rounded-md transition-all font-bold text-sm active:scale-[0.98]">
                Simpan Profil
            </button>
        </form>
    </div>

    {{-- Password --}}
    <div class="bg-white rounded-xl p-8" style="box-shadow: 0px 1px 3px rgba(45,27,77,0.06);">
        <h2 class="text-xl font-bold text-on-surface mb-6">Ubah Password</h2>

        <form method="POST" action="{{ route('profile.password') }}" class="space-y-6">
            @csrf
            @method('PUT')

            <div>
                <label for="current_password" class="block text-[10px] font-bold uppercase tracking-[0.12em] text-on-surface/50 mb-2">Password Saat Ini</label>
                <input id="current_password" name="current_password" type="password" required
                    class="w-full bg-surface-container-low rounded-md py-3 px-4 text-sm border-none @error('current_password') ring-2 ring-error/30 @enderror">
                @error('current_password') <p class="text-xs text-error mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="new_password" class="block text-[10px] font-bold uppercase tracking-[0.12em] text-on-surface/50 mb-2">Password Baru</label>
                <input id="new_password" name="password" type="password" required
                    class="w-full bg-surface-container-low rounded-md py-3 px-4 text-sm border-none @error('password') ring-2 ring-error/30 @enderror">
                @error('password') <p class="text-xs text-error mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="password_confirmation" class="block text-[10px] font-bold uppercase tracking-[0.12em] text-on-surface/50 mb-2">Konfirmasi Password Baru</label>
                <input id="password_confirmation" name="password_confirmation" type="password" required
                    class="w-full bg-surface-container-low rounded-md py-3 px-4 text-sm border-none">
            </div>

            <button type="submit" class="py-3 px-6 bg-primary hover:bg-primary-dark text-white rounded-md transition-all font-bold text-sm active:scale-[0.98]">
                Ubah Password
            </button>
        </form>
    </div>
</div>
@endsection
