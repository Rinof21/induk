<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk Pengelola - Sistem Informasi Kepegawaian & Fakultas UNTAN</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <!-- Google Fonts Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-[#0f172a] text-slate-800 min-h-screen flex items-center justify-center p-4">

    <div class="max-w-md w-full">
        <!-- Brand Header -->
        <div class="text-center mb-6">
            <div class="inline-flex items-center justify-center space-x-3 mb-3">
                <div class="w-12 h-12 rounded-2xl bg-blue-600 flex items-center justify-center text-white font-bold text-sm shadow-lg shadow-blue-500/30">
                    BLU
                </div>
                <div class="w-12 h-12 rounded-2xl bg-amber-500 flex items-center justify-center text-white font-bold text-lg shadow-lg shadow-amber-500/30">
                    <i class="fa-solid fa-graduation-cap"></i>
                </div>
            </div>
            <h1 class="text-xl font-bold text-white tracking-tight">Universitas Tanjungpura</h1>
            <p class="text-xs text-blue-300 mt-1">Panel Pengelola Dosen, Penguji, & Pasien Standar</p>
        </div>

        <!-- Card Login -->
        <div class="bg-white rounded-3xl shadow-2xl p-6 sm:p-8 border border-slate-100">
            <div class="mb-6">
                <h2 class="text-lg font-bold text-slate-900">Masuk ke Sistem</h2>
                <p class="text-xs text-slate-500 mt-0.5">Gunakan akun staf atau administrator fakultas Anda</p>
            </div>

            <!-- Flash Error -->
            @if($errors->any())
                <div class="mb-5 p-3.5 rounded-xl bg-rose-50 border border-rose-200 text-xs text-rose-700 space-y-1">
                    @foreach($errors->all() as $error)
                        <div class="flex items-center space-x-2">
                            <i class="fa-solid fa-circle-exclamation text-rose-500"></i>
                            <span>{{ $error }}</span>
                        </div>
                    @endforeach
                </div>
            @endif

            <form action="{{ route('login.post') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label for="email" class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">
                        Alamat Email
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <i class="fa-regular fa-envelope"></i>
                        </div>
                        <input type="email" name="email" id="email" required autofocus
                               placeholder="nama@untan.ac.id" 
                               value="{{ old('email') }}"
                               class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:bg-white transition">
                    </div>
                </div>

                <div>
                    <label for="password" class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">
                        Kata Sandi
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <i class="fa-solid fa-lock"></i>
                        </div>
                        <input type="password" name="password" id="password" required
                               placeholder="••••••••" 
                               class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:bg-white transition">
                    </div>
                </div>

                <div class="flex items-center justify-between text-xs pt-1">
                    <label class="flex items-center space-x-2 cursor-pointer text-slate-600">
                        <input type="checkbox" name="remember" class="w-4 h-4 rounded text-blue-600 focus:ring-blue-500 border-slate-300">
                        <span>Ingat saya di perangkat ini</span>
                    </label>
                </div>

                <button type="submit" 
                        class="w-full py-3 px-4 bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white font-semibold text-sm rounded-xl shadow-lg shadow-blue-600/30 transition duration-150 flex items-center justify-center space-x-2">
                    <i class="fa-solid fa-right-to-bracket text-xs"></i>
                    <span>Masuk ke Dashboard</span>
                </button>
            </form>

            <!-- Quick Demo Fill Buttons -->
            <div class="mt-6 pt-5 border-t border-slate-100">
                <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400 mb-2">Akun Demo Cepat (Password: password):</p>
                <div class="grid grid-cols-3 gap-2">
                    <button type="button" onclick="fillAccount('admin@untan.ac.id')"
                            class="py-1.5 px-2 bg-slate-50 hover:bg-blue-50 border border-slate-200 hover:border-blue-200 rounded-lg text-[11px] font-medium text-slate-700 text-center transition">
                        Super Admin
                    </button>
                    <button type="button" onclick="fillAccount('fk@untan.ac.id')"
                            class="py-1.5 px-2 bg-slate-50 hover:bg-blue-50 border border-slate-200 hover:border-blue-200 rounded-lg text-[11px] font-medium text-slate-700 text-center transition">
                        Admin FK
                    </button>
                    <button type="button" onclick="fillAccount('staf@untan.ac.id')"
                            class="py-1.5 px-2 bg-slate-50 hover:bg-blue-50 border border-slate-200 hover:border-blue-200 rounded-lg text-[11px] font-medium text-slate-700 text-center transition">
                        Staf Fakultas
                    </button>
                </div>
            </div>

            <!-- Link Kembali ke Portal Publik -->
            <div class="mt-4 text-center">
                <a href="{{ route('publik.dosen') }}" class="text-xs font-semibold text-slate-500 hover:text-blue-600 transition inline-flex items-center space-x-1">
                    <i class="fa-solid fa-arrow-left text-[10px]"></i>
                    <span>Kembali ke Halaman Publik</span>
                </a>
            </div>
        </div>
    </div>

    <script>
        function fillAccount(email) {
            document.getElementById('email').value = email;
            document.getElementById('password').value = 'password';
        }
    </script>
</body>
</html>

