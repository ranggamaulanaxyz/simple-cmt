@extends('layouts.guest')

@section('content')
<div class="w-full max-w-md mx-auto px-4">
    {{-- Logo Card --}}
    <div class="bg-white rounded-xl p-10" style="box-shadow: 0px 24px 48px rgba(45,27,77,0.1);">

        {{-- Branding --}}
        <div class="text-center mb-10">
            <div class="mb-5">
                <img src="{{ asset('logo.png') }}" alt="CMT Logo" class="w-20 h-20 object-contain mx-auto">
            </div>
            <h1 class="text-2xl font-extrabold text-on-surface tracking-tight">SIMPEL-CMT</h1>
            <p class="text-xs text-on-surface/50 mt-2 uppercase tracking-[0.15em] font-bold">Sistem Pelaporan Kerja</p>
        </div>

        {{-- Error Messages --}}
        @if($errors->any())
        <div class="mb-6 p-4 bg-error-bg rounded-lg" style="border-left: 3px solid #DC2626;">
            @foreach($errors->all() as $error)
            <p class="text-sm font-semibold text-error">{{ $error }}</p>
            @endforeach
        </div>
        @endif

        {{-- Login Form --}}
        <form method="POST" action="{{ route('login') }}" class="space-y-5">
            @csrf

            {{-- Email --}}
            <div>
                <label for="email" class="block text-[10px] font-bold uppercase tracking-[0.12em] text-on-surface/50 mb-2">Email</label>
                <div class="relative">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface/30 text-lg">mail</span>
                    <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus
                        class="w-full bg-surface-container-low rounded-md py-3 pl-10 pr-4 text-sm text-on-surface placeholder:text-on-surface/30 border-none transition-all"
                        placeholder="nama@email.com">
                </div>
            </div>

            {{-- Password --}}
            <div>
                <label for="password" class="block text-[10px] font-bold uppercase tracking-[0.12em] text-on-surface/50 mb-2">Password</label>
                <div class="relative" x-data="{ show: false }">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface/30 text-lg">lock</span>
                    <input id="password" name="password" :type="show ? 'text' : 'password'" required
                        class="w-full bg-surface-container-low rounded-md py-3 pl-10 pr-12 text-sm text-on-surface placeholder:text-on-surface/30 border-none transition-all"
                        placeholder="••••••••">
                    <button type="button" @click="show = !show" class="absolute right-3 top-1/2 -translate-y-1/2 text-on-surface/30 hover:text-primary transition-colors">
                        <span class="material-symbols-outlined text-lg" x-text="show ? 'visibility_off' : 'visibility'"></span>
                    </button>
                </div>
            </div>

            {{-- Remember Me --}}
            <div class="flex items-center gap-2">
                <input id="remember" name="remember" type="checkbox"
                    class="w-4 h-4 rounded-sm border-outline-variant text-primary focus:ring-primary/20">
                <label for="remember" class="text-xs text-on-surface/60 font-medium">Ingat saya</label>
            </div>

            {{-- Submit --}}
            <button type="submit"
                class="w-full py-3 bg-primary hover:bg-primary-dark text-white rounded-md transition-all font-bold flex items-center justify-center gap-2 active:scale-[0.98] text-sm"
                style="box-shadow: 0px 2px 8px rgba(124,58,237,0.3);">
                <span class="material-symbols-outlined text-base">login</span>
                <span>Masuk</span>
            </button>
        </form>
    </div>

    {{-- Footer --}}
    <p class="text-center text-[10px] text-on-surface/30 mt-6 uppercase tracking-[0.12em] font-bold">
        PT. Wahana Cahaya Sukses © {{ date('Y') }}
    </p>
</div>
@endsection
