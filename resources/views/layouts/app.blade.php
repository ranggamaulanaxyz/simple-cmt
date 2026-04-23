<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') — SIMPEL-CMT</title>
    <meta name="description" content="Sistem Pelaporan Pekerjaan - PT. Wahana Cahaya Sukses">
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet" />
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-surface-dim text-on-surface antialiased overflow-x-hidden" x-data="{ 
          sidebarOpen: window.innerWidth >= 1024,
          isDesktop: window.innerWidth >= 1024
      }" x-init="
          $watch('sidebarOpen', value => { 
              if (!isDesktop && value) document.body.classList.add('overflow-hidden'); 
              else document.body.classList.remove('overflow-hidden'); 
          });
      " @resize.window.debounce.50ms="
          let previouslyDesktop = isDesktop;
          isDesktop = window.innerWidth >= 1024;
          if (isDesktop && !previouslyDesktop) sidebarOpen = true;
          if (!isDesktop && previouslyDesktop) sidebarOpen = false;
      ">

    {{-- Mobile Sidebar Overlay --}}
    <div x-show="sidebarOpen && !isDesktop" @click="sidebarOpen = false" class="fixed inset-0 bg-on-surface/20 backdrop-blur-sm z-40 lg:hidden" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
    </div>

    {{-- Sidebar Rail/Mini --}}
    <aside class="fixed left-0 top-0 h-full flex flex-col gap-2 py-4 bg-white z-50 shadow-md transform-gpu transition-all duration-300 ease-in-out will-change-[width,transform]" :class="{
               'w-72 translate-x-0': sidebarOpen,
               'w-20 translate-x-0': !sidebarOpen && isDesktop,
               'w-72 -translate-x-full': !sidebarOpen && !isDesktop
           }" x-cloak>

        {{-- Logo & Toggle --}}
        <div class="mb-8 pt-2 flex items-center transition-all duration-300 ease-in-out" :class="sidebarOpen || !isDesktop ? 'px-8 justify-between' : 'px-0 justify-center'">
            <div x-show="sidebarOpen || !isDesktop" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 -translate-x-4" x-transition:enter-end="opacity-100 translate-x-0" class="flex items-center gap-3">
                <img src="{{ asset('logo.png') }}" alt="CMT Logo" class="w-8 h-8 object-contain rounded-md shadow-sm">
                <div>
                    <span class="text-sm font-extrabold text-primary tracking-tight">SIMPEL-CMT</span>
                    <p class="uppercase tracking-[0.12em] text-[8px] font-bold text-on-surface/50 mt-1">Sistem Pelaporan</p>
                </div>
            </div>
            <div x-show="!sidebarOpen && isDesktop" class="flex items-center justify-center w-full">
                <img src="{{ asset('logo.png') }}" alt="CMT Logo" class="w-8 h-8 object-contain rounded-md shadow-sm">
            </div>
            <button @click="sidebarOpen = !sidebarOpen" class="material-symbols-outlined text-on-surface-variant hover:text-primary transition-colors" x-show="!isDesktop">
                <span x-text="sidebarOpen ? 'menu_open' : 'menu'"></span>
            </button>
        </div>

        {{-- Navigation --}}
        <nav class="flex-1 px-4 space-y-1 overflow-y-auto overflow-x-hidden">
            @php $user = auth()->user(); @endphp

            <x-sidebar-link href="{{ route('dashboard') }}" icon="analytics" :active="request()->routeIs('dashboard')">
                Dashboard
            </x-sidebar-link>

            <x-sidebar-link href="{{ route('reports.index') }}" icon="description" :active="request()->routeIs('reports.*')">
                Laporan
            </x-sidebar-link>

            @if($user->isAdmin())
                <x-sidebar-link href="{{ route('users.index') }}" icon="group" :active="request()->routeIs('users.*')">
                    Manajemen User
                </x-sidebar-link>
                <x-sidebar-link href="{{ route('configuration.index') }}" icon="settings" :active="request()->routeIs('configuration.*')">
                    Konfigurasi
                </x-sidebar-link>
            @endif
        </nav>



    </aside>

    {{-- Content Area --}}
    <main class="transition-all duration-300 ease-in-out will-change-[margin]" :class="sidebarOpen ? (isDesktop ? 'ml-72' : 'ml-0') : (isDesktop ? 'ml-20' : 'ml-0')">

        {{-- Top Bar --}}
        <header class="flex justify-between items-center w-full px-4 lg:px-8 py-3 sticky top-0 z-40 bg-white/95 backdrop-blur-sm" style="box-shadow: 0 1px 0 rgba(124,58,237,0.08);">
            <div class="flex items-center gap-4 lg:gap-6 flex-1 text-nowrap">
                <button @click="sidebarOpen = !sidebarOpen" class="material-symbols-outlined text-on-surface-variant hover:text-primary transition-colors">
                    menu
                </button>
                <h1 class="text-sm tracking-tight font-bold text-on-surface truncate">@yield('page-title', 'Dashboard')</h1>
            </div>
            {{-- User Profile Dropdown --}}
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open" @click.away="open = false" class="flex items-center gap-3 pl-4 lg:pl-6 hover:opacity-80 transition-opacity cursor-pointer text-left">
                    <div class="flex items-center gap-3">
                        <div class="text-right hidden sm:block">
                            <p class="text-xs font-bold text-primary">{{ auth()->user()->name }}</p>
                            <p class="text-[10px] text-on-surface/50 uppercase tracking-[0.15em] font-bold">{{ auth()->user()->role }}</p>
                        </div>
                        <div class="w-9 h-9 lg:w-10 lg:h-10 rounded-full bg-primary/10 flex items-center justify-center text-sm font-bold text-primary" style="border: 2px solid rgba(124,58,237,0.2);">
                            {{ auth()->user()->initials }}
                        </div>
                    </div>
                </button>

                {{-- Dropdown Menu --}}
                <div x-show="open" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="transform opacity-100 scale-100" x-transition:leave-end="transform opacity-0 scale-95" class="absolute right-0 mt-3 w-48 bg-white rounded-xl shadow-lg border border-surface-container py-2 flex flex-col z-50 overflow-hidden" style="box-shadow: 0px 4px 12px rgba(45,27,77,0.08);" x-cloak>

                    <div class="px-4 py-2 border-b border-surface-container mb-1 block sm:hidden">
                        <p class="text-xs font-bold text-primary truncate">{{ auth()->user()->name }}</p>
                        <p class="text-[10px] text-on-surface/50 uppercase tracking-[0.15em] font-bold">{{ auth()->user()->role }}</p>
                    </div>

                    <a href="{{ route('profile.edit') }}" class="px-4 py-2 text-xs font-bold text-on-surface-variant hover:bg-primary/5 hover:text-primary transition-colors flex items-center gap-2">
                        <span class="material-symbols-outlined text-base">person</span>
                        Profil
                    </a>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full text-left px-4 py-2 text-xs font-bold text-error hover:bg-error-bg transition-colors flex items-center gap-2">
                            <span class="material-symbols-outlined text-base">logout</span>
                            Keluar
                        </button>
                    </form>
                </div>
            </div>
        </header>

        {{-- Messages --}}
        @if(session('success'))
            <div class="mx-4 lg:mx-8 mt-4" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
                <div class="p-4 bg-success-bg rounded-xl flex items-center gap-3" style="border-left: 3px solid #16A34A;">
                    <span class="material-symbols-outlined text-success text-lg">check_circle</span>
                    <p class="text-sm font-semibold text-success">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="mx-4 lg:mx-8 mt-4" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
                <div class="p-4 bg-error-bg rounded-xl flex items-center gap-3" style="border-left: 3px solid #DC2626;">
                    <span class="material-symbols-outlined text-error text-lg">error</span>
                    <p class="text-sm font-semibold text-error">{{ session('error') }}</p>
                </div>
            </div>
        @endif

        {{-- Page Content --}}
        <div class="p-4 lg:p-8">
            @yield('content')
        </div>
    </main>

    @stack('scripts')
</body>

</html>