<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Portal Data Fakultas & Kepegawaian') - Universitas Tanjungpura</title>
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
        body, button, input, select, textarea, table, th, td { 
            font-family: Arial, Helvetica, sans-serif !important; 
        }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-[#f8fafc] text-slate-800 min-h-screen flex flex-col">

    <!-- Top Navigation Bar (Meniru gaya screenshot UNTAN) -->
    <header class="bg-white border-b border-slate-200 sticky top-0 z-40 shadow-sm">
        <div class="w-full max-w-[88%] mx-auto px-6 sm:px-8 lg:px-10">
            <div class="flex items-center justify-between h-20">
                <!-- Branding Left -->
                <div class="flex items-center space-x-3">
                    <!-- Logo BLU & UNTAN -->
                    <div class="flex items-center space-x-2">
                        <div class="w-10 h-10 rounded-full bg-blue-600 flex items-center justify-center text-white font-bold text-xs shadow-sm ring-2 ring-blue-100">
                            BLU
                        </div>
                        <div class="w-10 h-10 rounded-full bg-amber-500 flex items-center justify-center text-white font-bold text-xs shadow-sm ring-2 ring-amber-100">
                            <i class="fa-solid fa-graduation-cap text-base"></i>
                        </div>
                    </div>
                    <div>
                        <a href="{{ route('publik.dosen') }}" class="block">
                            <h1 class="text-base font-bold text-slate-900 leading-tight">Fakultas Kedokteran</h1>
                            <p class="text-xs text-slate-500 font-normal">Universitas Tanjungpura</p>
                        </a>
                    </div>
                </div>

                <!-- Navigation Tabs -->
                <nav class="hidden md:flex items-center space-x-1 lg:space-x-3">
                    <a href="{{ route('publik.dosen') }}" 
                       class="px-3.5 py-2 rounded-lg text-sm font-normal transition-all duration-150 {{ request()->routeIs('publik.dosen') ? 'bg-blue-50 text-blue-700 shadow-sm' : 'text-slate-600 hover:text-blue-600 hover:bg-slate-50' }}">
                        <i class="fa-solid fa-chalkboard-user mr-1.5 {{ request()->routeIs('publik.dosen') ? 'text-blue-600' : 'text-slate-400' }}"></i>
                        Data Dosen
                    </a>
                    <a href="{{ route('publik.eksternal') }}" 
                       class="px-3.5 py-2 rounded-lg text-sm font-normal transition-all duration-150 {{ request()->routeIs('publik.eksternal') ? 'bg-blue-50 text-blue-700 shadow-sm' : 'text-slate-600 hover:text-blue-600 hover:bg-slate-50' }}">
                        <i class="fa-solid fa-user-group mr-1.5 {{ request()->routeIs('publik.eksternal') ? 'text-blue-600' : 'text-slate-400' }}"></i>
                        Data Eksternal
                    </a>
                    <a href="{{ route('publik.penguji') }}" 
                       class="px-3.5 py-2 rounded-lg text-sm font-normal transition-all duration-150 {{ request()->routeIs('publik.penguji') ? 'bg-blue-50 text-blue-700 shadow-sm' : 'text-slate-600 hover:text-blue-600 hover:bg-slate-50' }}">
                        <i class="fa-solid fa-user-check mr-1.5 {{ request()->routeIs('publik.penguji') ? 'text-blue-600' : 'text-slate-400' }}"></i>
                        Data Penguji
                    </a>
                    <a href="{{ route('publik.pasien-standar') }}" 
                       class="px-3.5 py-2 rounded-lg text-sm font-normal transition-all duration-150 {{ request()->routeIs('publik.pasien-standar') ? 'bg-blue-50 text-blue-700 shadow-sm' : 'text-slate-600 hover:text-blue-600 hover:bg-slate-50' }}">
                        <i class="fa-solid fa-hospital-user mr-1.5 {{ request()->routeIs('publik.pasien-standar') ? 'text-blue-600' : 'text-slate-400' }}"></i>
                        Pasien Standar (PS)
                    </a>
                </nav>

                <!-- Actions Right -->
                <div class="flex items-center space-x-3">
                    @if(session('public_authenticated'))
                        <div class="hidden sm:flex items-center bg-emerald-50 border border-emerald-200 text-emerald-700 text-xs px-2.5 py-1 rounded-full font-medium">
                            <span class="w-2 h-2 rounded-full bg-emerald-500 mr-1.5 animate-pulse"></span>
                            Token Terverifikasi
                            <a href="{{ route('publik.token.logout') }}" title="Kunci / Keluar Sesi Publik" class="ml-2 text-emerald-800 hover:text-emerald-950">
                                <i class="fa-solid fa-lock text-xs"></i>
                            </a>
                        </div>
                    @endif

                    @auth
                        <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold rounded-lg shadow-sm transition">
                            <i class="fa-solid fa-gauge-high mr-1.5"></i>
                            Panel Pengelola
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="inline-flex items-center px-4 py-2 border border-slate-300 hover:border-blue-600 bg-white hover:bg-blue-50 text-slate-700 hover:text-blue-700 text-xs font-semibold rounded-lg shadow-sm transition">
                            <i class="fa-solid fa-right-to-bracket mr-1.5"></i>
                            Login Pengelola
                        </a>
                    @endauth
                </div>
            </div>

            <!-- Mobile Nav Sub-menu -->
            <div class="md:hidden flex overflow-x-auto space-x-2 py-2 border-t border-slate-100 text-xs">
                <a href="{{ route('publik.dosen') }}" class="whitespace-nowrap px-3 py-1.5 rounded-md font-normal {{ request()->routeIs('publik.dosen') ? 'bg-blue-600 text-white' : 'text-slate-600 bg-slate-100' }}">
                    Data Dosen
                </a>
                <a href="{{ route('publik.eksternal') }}" class="whitespace-nowrap px-3 py-1.5 rounded-md font-normal {{ request()->routeIs('publik.eksternal') ? 'bg-blue-600 text-white' : 'text-slate-600 bg-slate-100' }}">
                    Data Eksternal
                </a>
                <a href="{{ route('publik.penguji') }}" class="whitespace-nowrap px-3 py-1.5 rounded-md font-normal {{ request()->routeIs('publik.penguji') ? 'bg-blue-600 text-white' : 'text-slate-600 bg-slate-100' }}">
                    Data Penguji
                </a>
                <a href="{{ route('publik.pasien-standar') }}" class="whitespace-nowrap px-3 py-1.5 rounded-md font-normal {{ request()->routeIs('publik.pasien-standar') ? 'bg-blue-600 text-white' : 'text-slate-600 bg-slate-100' }}">
                    Pasien Standar
                </a>
            </div>
        </div>
    </header>

    <!-- Main Content Area -->
    <main class="flex-1 w-full max-w-[88%] mx-auto px-6 sm:px-8 lg:px-10 py-8">
        <!-- Flash Alert -->
        @if(session('success'))
            <div class="mb-6 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm flex items-center justify-between shadow-sm">
                <div class="flex items-center space-x-2">
                    <i class="fa-solid fa-circle-check text-emerald-600 text-base"></i>
                    <span>{{ session('success') }}</span>
                </div>
                <button onclick="this.parentElement.remove()" class="text-emerald-600 hover:text-emerald-900">&times;</button>
            </div>
        @endif

        @if(session('info'))
            <div class="mb-6 p-4 rounded-xl bg-blue-50 border border-blue-200 text-blue-800 text-sm flex items-center justify-between shadow-sm">
                <div class="flex items-center space-x-2">
                    <i class="fa-solid fa-circle-info text-blue-600 text-base"></i>
                    <span>{{ session('info') }}</span>
                </div>
                <button onclick="this.parentElement.remove()" class="text-blue-600 hover:text-blue-900">&times;</button>
            </div>
        @endif

        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-slate-200 py-6 mt-auto">
        <div class="w-full max-w-[88%] mx-auto px-6 sm:px-8 lg:px-10 flex flex-col sm:flex-row items-center justify-between text-xs text-slate-500 space-y-3 sm:space-y-0">
            <div class="flex items-center space-x-2">
                <span>&copy; {{ date('Y') }} Universitas Tanjungpura. Sistem Terintegrasi Data Dosen, Penguji, dan Pasien Standar.</span>
            </div>
            <div class="flex items-center space-x-4">
                <span>Fakultas Kedokteran</span>
                <span>&bull;</span>
                <span>Universitas Tanjungpura</span>
            </div>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>

