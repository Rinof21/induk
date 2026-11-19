<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Panel') - Sistem Informasi Kepegawaian & Fakultas</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <!-- Google Fonts Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-[#f1f5f9] text-slate-800 min-h-screen flex" x-data="{ sidebarOpen: false }">

    <!-- Mobile Sidebar Backdrop -->
    <div x-show="sidebarOpen" @click="sidebarOpen = false" x-cloak class="fixed inset-0 bg-slate-900/50 z-40 lg:hidden"></div>

    <!-- Sidebar -->
    <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" 
           class="fixed lg:static inset-y-0 left-0 z-50 w-64 bg-slate-900 text-slate-200 flex flex-col transition-transform duration-200 ease-in-out lg:translate-x-0 shadow-xl">
        
        <!-- Sidebar Brand -->
        <div class="h-20 flex items-center px-6 border-b border-slate-800 bg-slate-950/40">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 rounded-xl bg-blue-600 flex items-center justify-center text-white font-bold text-sm shadow-md shadow-blue-500/20">
                    <i class="fa-solid fa-building-columns"></i>
                </div>
                <div>
                    <h2 class="font-bold text-sm text-white tracking-wide">SIM-FAKULTAS</h2>
                    <p class="text-[11px] text-blue-400 font-medium">Universitas Tanjungpura</p>
                </div>
            </div>
        </div>

        <!-- Navigation Menu -->
        <div class="flex-1 overflow-y-auto px-4 py-6 space-y-6">
            <!-- Group 1: Menu Utama -->
            <div>
                <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400 px-3 mb-2">Navigasi Utama</p>
                <div class="space-y-1">
                    <a href="{{ route('admin.dashboard') }}" 
                       class="flex items-center space-x-3 px-3 py-2.5 rounded-xl text-sm font-medium transition {{ request()->routeIs('admin.dashboard') ? 'bg-blue-600 text-white shadow-md shadow-blue-600/30' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                        <i class="fa-solid fa-gauge-high w-5 text-center text-base {{ request()->routeIs('admin.dashboard') ? 'text-white' : 'text-slate-400' }}"></i>
                        <span>Dashboard</span>
                    </a>
                </div>
            </div>

            <!-- Group 2: Modul Data Master & CRUD -->
            <div>
                <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400 px-3 mb-2">Manajemen Data</p>
                <div class="space-y-1">
                    <a href="{{ route('admin.dosen.index') }}" 
                       class="flex items-center space-x-3 px-3 py-2.5 rounded-xl text-sm font-medium transition {{ request()->routeIs('admin.dosen.*') ? 'bg-blue-600 text-white shadow-md shadow-blue-600/30' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                        <i class="fa-solid fa-chalkboard-user w-5 text-center text-base {{ request()->routeIs('admin.dosen.*') ? 'text-white' : 'text-slate-400' }}"></i>
                        <span>Data Dosen</span>
                    </a>

                    <a href="{{ route('admin.eksternal.index') }}" 
                       class="flex items-center space-x-3 px-3 py-2.5 rounded-xl text-sm font-medium transition {{ request()->routeIs('admin.eksternal.*') ? 'bg-blue-600 text-white shadow-md shadow-blue-600/30' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                        <i class="fa-solid fa-user-group w-5 text-center text-base {{ request()->routeIs('admin.eksternal.*') ? 'text-white' : 'text-slate-400' }}"></i>
                        <span>Data Eksternal</span>
                    </a>

                    <a href="{{ route('admin.penguji.index') }}" 
                       class="flex items-center space-x-3 px-3 py-2.5 rounded-xl text-sm font-medium transition {{ request()->routeIs('admin.penguji.*') ? 'bg-blue-600 text-white shadow-md shadow-blue-600/30' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                        <i class="fa-solid fa-user-check w-5 text-center text-base {{ request()->routeIs('admin.penguji.*') ? 'text-white' : 'text-slate-400' }}"></i>
                        <span>Data Penguji</span>
                    </a>

                    <a href="{{ route('admin.pasien-standar.index') }}" 
                       class="flex items-center space-x-3 px-3 py-2.5 rounded-xl text-sm font-medium transition {{ request()->routeIs('admin.pasien-standar.*') ? 'bg-blue-600 text-white shadow-md shadow-blue-600/30' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                        <i class="fa-solid fa-hospital-user w-5 text-center text-base {{ request()->routeIs('admin.pasien-standar.*') ? 'text-white' : 'text-slate-400' }}"></i>
                        <span>Pasien Standar (PS)</span>
                    </a>

                    <!-- Kotak Sampah (Soft Delete & Trash) -->
                    <a href="{{ route('admin.trash.index') }}" 
                       class="flex items-center space-x-3 px-3 py-2.5 rounded-xl text-sm font-medium transition {{ request()->routeIs('admin.trash.*') ? 'bg-rose-600 text-white shadow-md shadow-rose-600/30' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                        <i class="fa-solid fa-trash-can w-5 text-center text-base {{ request()->routeIs('admin.trash.*') ? 'text-white' : 'text-slate-400' }}"></i>
                        <span>Kotak Sampah</span>
                    </a>
                </div>
            </div>

            <!-- Group 3: Akses Publik & Spatie Permission -->
            <div>
                <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400 px-3 mb-2">Konfigurasi & Akses</p>
                <div class="space-y-1">
                    <a href="{{ route('admin.pengaturan-publik.index') }}" 
                       class="flex items-center space-x-3 px-3 py-2.5 rounded-xl text-sm font-medium transition {{ request()->routeIs('admin.pengaturan-publik.*') ? 'bg-blue-600 text-white shadow-md shadow-blue-600/30' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                        <i class="fa-solid fa-key w-5 text-center text-base {{ request()->routeIs('admin.pengaturan-publik.*') ? 'text-white' : 'text-slate-400' }}"></i>
                        <span>Akses Token Publik</span>
                    </a>

                    @can('kelola-user')
                    <a href="{{ route('admin.users.index') }}" 
                       class="flex items-center space-x-3 px-3 py-2.5 rounded-xl text-sm font-medium transition {{ request()->routeIs('admin.users.*') ? 'bg-blue-600 text-white shadow-md shadow-blue-600/30' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                        <i class="fa-solid fa-users-gear w-5 text-center text-base {{ request()->routeIs('admin.users.*') ? 'text-white' : 'text-slate-400' }}"></i>
                        <span>Pengguna & Role</span>
                    </a>
                    @endcan
                </div>
            </div>
        </div>

        <!-- Sidebar Footer -->
        <div class="p-4 border-t border-slate-800 bg-slate-950/60">
            <a href="{{ route('publik.dosen') }}" target="_blank" 
               class="flex items-center justify-center space-x-2 w-full py-2 px-3 bg-slate-800 hover:bg-slate-700 text-slate-200 text-xs font-semibold rounded-lg transition">
                <i class="fa-solid fa-arrow-up-right-from-square"></i>
                <span>Buka Portal Publik</span>
            </a>
        </div>
    </aside>

    <!-- Main Content Container -->
    <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
        
        <!-- Top Navbar -->
        <header class="h-20 bg-white border-b border-slate-200 flex items-center justify-between px-4 sm:px-6 lg:px-8 sticky top-0 z-30">
            <!-- Mobile Toggle -->
            <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden p-2 rounded-lg text-slate-600 hover:bg-slate-100">
                <i class="fa-solid fa-bars text-lg"></i>
            </button>

            <!-- Title & Breadcrumb -->
            <div class="hidden sm:block">
                <h1 class="text-lg font-bold text-slate-900 leading-tight">@yield('page_title', 'Admin Panel')</h1>
                <p class="text-xs text-slate-500">Kelola data dosen, penguji, pasien standar, dan izin akses Spatie</p>
            </div>

            <!-- Profile & Logout Action -->
            <div class="flex items-center space-x-4">
                <div class="text-right">
                    <p class="text-sm font-semibold text-slate-800">{{ Auth::user()->name }}</p>
                    <div class="flex items-center justify-end space-x-1.5">
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-blue-100 text-blue-800">
                            {{ Auth::user()->roles->pluck('name')->first() ?? 'Pengguna' }}
                        </span>
                        @if(Auth::user()->fakultas)
                            <span class="text-[11px] text-slate-400">({{ Auth::user()->fakultas }})</span>
                        @endif
                    </div>
                </div>

                <!-- Avatar -->
                <div class="w-10 h-10 rounded-full bg-slate-800 text-white font-bold flex items-center justify-center text-sm shadow">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>

                <!-- Logout Button -->
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" title="Keluar dari Sistem" 
                            class="p-2.5 rounded-lg text-rose-600 hover:bg-rose-50 border border-slate-200 hover:border-rose-200 transition">
                        <i class="fa-solid fa-arrow-right-from-bracket"></i>
                    </button>
                </form>
            </div>
        </header>

        <!-- Main Workspace -->
        <main class="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-8">
            <!-- Global Flash Messages -->
            @if(session('success'))
                <div class="mb-6 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm flex items-center justify-between shadow-sm animate-fade-in">
                    <div class="flex items-center space-x-2">
                        <i class="fa-solid fa-circle-check text-emerald-600 text-base"></i>
                        <span>{{ session('success') }}</span>
                    </div>
                    <button onclick="this.parentElement.remove()" class="text-emerald-600 hover:text-emerald-900 font-bold">&times;</button>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-sm flex items-center justify-between shadow-sm">
                    <div class="flex items-center space-x-2">
                        <i class="fa-solid fa-triangle-exclamation text-rose-600 text-base"></i>
                        <span>{{ session('error') }}</span>
                    </div>
                    <button onclick="this.parentElement.remove()" class="text-rose-600 hover:text-rose-900 font-bold">&times;</button>
                </div>
            @endif

            @if($errors->any())
                <div class="mb-6 p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-sm shadow-sm">
                    <div class="flex items-center space-x-2 font-bold mb-1">
                        <i class="fa-solid fa-circle-exclamation text-rose-600"></i>
                        <span>Terjadi kesalahan pengisian formulir:</span>
                    </div>
                    <ul class="list-disc list-inside space-y-0.5 text-xs text-rose-700 ml-4">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    @stack('scripts')
</body>
</html>

