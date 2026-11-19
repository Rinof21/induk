<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Akses Token Diperlukan - Portal Informasi Fakultas UNTAN</title>
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
<body class="bg-gradient-to-br from-slate-900 via-blue-950 to-slate-900 min-h-screen flex items-center justify-center p-4">

    <div class="max-w-md w-full">
        <!-- Logo & Header Instansi -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center space-x-3 mb-4">
                <div class="w-12 h-12 rounded-2xl bg-blue-600 flex items-center justify-center text-white font-bold text-sm shadow-lg shadow-blue-500/30 ring-4 ring-blue-500/20">
                    BLU
                </div>
                <div class="w-12 h-12 rounded-2xl bg-amber-500 flex items-center justify-center text-white font-bold text-lg shadow-lg shadow-amber-500/30 ring-4 ring-amber-500/20">
                    <i class="fa-solid fa-graduation-cap"></i>
                </div>
            </div>
            <h1 class="text-xl font-bold text-white tracking-tight">{{ $universitas }}</h1>
            <p class="text-xs text-blue-200 mt-1">{{ $instansi }}</p>
        </div>

        <!-- Token Gate Card -->
        <div class="bg-white rounded-3xl shadow-2xl p-6 sm:p-8 border border-white/20 backdrop-blur">
            <div class="flex items-center space-x-3 pb-5 border-b border-slate-100">
                <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-lg">
                    <i class="fa-solid fa-shield-halved"></i>
                </div>
                <div>
                    <h2 class="text-base font-bold text-slate-900">Akses Terproteksi Token</h2>
                    <p class="text-xs text-slate-500">Portal Data Dosen, Penguji & Pasien Standar</p>
                </div>
            </div>

            <!-- Petunjuk dari Admin -->
            <div class="my-5 p-3.5 rounded-xl bg-slate-50 border border-slate-200/80 text-xs text-slate-600 leading-relaxed flex items-start space-x-3">
                <i class="fa-solid fa-circle-info text-blue-600 mt-0.5 text-sm"></i>
                <div>
                    <span class="font-semibold text-slate-800 block mb-0.5">Petunjuk Akses:</span>
                    <span>{{ $message }}</span>
                </div>
            </div>

            <!-- Flash Error Message -->
            @if($errors->has('token'))
                <div class="mb-5 p-3.5 rounded-xl bg-rose-50 border border-rose-200 text-xs text-rose-700 flex items-start space-x-2.5">
                    <i class="fa-solid fa-triangle-exclamation text-rose-500 mt-0.5"></i>
                    <span>{{ $errors->first('token') }}</span>
                </div>
            @endif

            <!-- Form Masukkan Token -->
            <form action="{{ route('publik.token.verify') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label for="token" class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">
                        Token Akses Fakultas
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <i class="fa-solid fa-key"></i>
                        </div>
                        <input type="text" name="token" id="token" required autofocus autocomplete="off"
                               placeholder="Contoh: kitasehat" 
                               value="{{ old('token') }}"
                               class="w-full pl-10 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-mono tracking-wider text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:bg-white transition">
                    </div>
                </div>

                <button type="submit" 
                        class="w-full py-3.5 px-4 bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white font-semibold text-sm rounded-xl shadow-lg shadow-blue-600/30 transition duration-150 flex items-center justify-center space-x-2">
                    <span>Buka Data Fakultas</span>
                    <i class="fa-solid fa-arrow-right text-xs"></i>
                </button>
            </form>

            <!-- Bantuan / Login Pengelola -->
            <div class="mt-6 pt-5 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500">
                <span>Pengelola Fakultas?</span>
                <a href="{{ route('login') }}" class="font-semibold text-blue-600 hover:text-blue-800 hover:underline inline-flex items-center space-x-1">
                    <span>Masuk ke Akun</span>
                    <i class="fa-solid fa-chevron-right text-[10px]"></i>
                </a>
            </div>
        </div>

        <!-- Footer Note -->
        <p class="text-center text-xs text-slate-400 mt-6">
            &copy; {{ date('Y') }} Universitas Tanjungpura &bull; Sistem Informasi Terpadu
        </p>
    </div>

</body>
</html>

