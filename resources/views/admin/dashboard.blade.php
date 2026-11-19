@extends('layouts.admin')

@section('title', 'Dashboard Pengelola')
@section('page_title', 'Dashboard Ringkasan')

@section('content')
<div class="space-y-8">

    <!-- Card Status Proteksi Token Akses Publik (Highlight Fitur Khusus) -->
    <div class="bg-gradient-to-r from-blue-900 via-indigo-900 to-slate-900 rounded-3xl p-6 sm:p-8 text-white shadow-xl relative overflow-hidden">
        <!-- Background Pattern -->
        <div class="absolute -right-10 -bottom-10 opacity-10 text-9xl pointer-events-none">
            <i class="fa-solid fa-key"></i>
        </div>

        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div class="space-y-2 max-w-xl">
                <div class="flex items-center space-x-3">
                    <span class="px-3 py-1 rounded-full text-xs font-bold tracking-wide uppercase {{ $tokenEnabled ? 'bg-emerald-500/20 text-emerald-300 border border-emerald-500/30' : 'bg-amber-500/20 text-amber-300 border border-amber-500/30' }}">
                        <i class="fa-solid fa-circle text-[8px] mr-1.5 {{ $tokenEnabled ? 'text-emerald-400' : 'text-amber-400' }}"></i>
                        Status Token: {{ $tokenEnabled ? 'AKTIF (Terproteksi)' : 'NONAKTIF (Publik Bebas Akses)' }}
                    </span>
                    <span class="text-xs text-slate-300 font-medium">Halaman Publik Portal</span>
                </div>
                <h3 class="text-xl sm:text-2xl font-black text-white">Pengaturan Akses Token Halaman Publik</h3>
                <p class="text-xs sm:text-sm text-slate-300 leading-relaxed">
                    @if($tokenEnabled)
                        Setiap civitas atau staf fakultas yang membuka halaman publik <strong>wajib memasukkan token</strong> atau menggunakan link tautan yang telah diverifikasi.
                    @else
                        Saat ini portal publik <strong>dapat diakses langsung</strong> oleh semua bagian fakultas tanpa perlu memasukkan token.
                    @endif
                </p>

                <!-- Current Token Display -->
                <div class="pt-2 flex flex-wrap items-center gap-3">
                    <span class="text-xs text-slate-300">Token Akses Saat Ini:</span>
                    <span class="font-mono text-sm bg-white/10 px-3 py-1 rounded-lg border border-white/20 font-bold tracking-widest text-amber-300">
                        {{ $currentToken }}
                    </span>
                </div>
            </div>

            <!-- Quick Action Buttons -->
            <div class="flex flex-col sm:flex-row md:flex-col gap-3 min-w-[220px]">
                <form action="{{ route('admin.pengaturan-publik.toggle') }}" method="POST">
                    @csrf
                    <button type="submit" 
                            class="w-full py-2.5 px-4 rounded-xl text-xs font-bold transition flex items-center justify-center space-x-2 {{ $tokenEnabled ? 'bg-rose-600 hover:bg-rose-700 text-white shadow-lg shadow-rose-600/30' : 'bg-emerald-600 hover:bg-emerald-700 text-white shadow-lg shadow-emerald-600/30' }}">
                        <i class="fa-solid {{ $tokenEnabled ? 'fa-lock-open' : 'fa-lock' }}"></i>
                        <span>{{ $tokenEnabled ? 'Nonaktifkan Token' : 'Aktifkan Proteksi Token' }}</span>
                    </button>
                </form>

                <a href="{{ route('admin.pengaturan-publik.index') }}" 
                   class="w-full py-2.5 px-4 bg-white/10 hover:bg-white/20 border border-white/20 text-white rounded-xl text-xs font-bold transition text-center flex items-center justify-center space-x-2">
                    <i class="fa-solid fa-gear"></i>
                    <span>Kelola Pengaturan Lengkap</span>
                </a>
            </div>
        </div>
    </div>

    <!-- 5 Metric Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
        <!-- Card 1: Dosen Internal -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Dosen Internal</p>
                <p class="text-2xl font-extrabold text-slate-900 mt-1">{{ number_format($stats['total_dosen']) }}</p>
                <p class="text-[11px] text-blue-600 font-semibold mt-1">{{ $stats['total_guru_besar'] }} Guru Besar</p>
            </div>
            <div class="w-11 h-11 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center text-lg">
                <i class="fa-solid fa-chalkboard-user"></i>
            </div>
        </div>

        <!-- Card 2: Tenaga Eksternal -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Data Eksternal</p>
                <p class="text-2xl font-extrabold text-slate-900 mt-1">{{ number_format($stats['total_eksternal']) }}</p>
                <p class="text-[11px] text-cyan-600 font-semibold mt-1">Dosen & Praktisi Luar</p>
            </div>
            <div class="w-11 h-11 rounded-2xl bg-cyan-50 text-cyan-600 flex items-center justify-center text-lg">
                <i class="fa-solid fa-user-group"></i>
            </div>
        </div>

        <!-- Card 3: Penguji -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Total Penguji</p>
                <p class="text-2xl font-extrabold text-slate-900 mt-1">{{ number_format($stats['total_penguji']) }}</p>
                <p class="text-[11px] text-emerald-600 font-semibold mt-1">{{ $stats['total_penguji_aktif'] }} Aktif Bertugas</p>
            </div>
            <div class="w-11 h-11 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-lg">
                <i class="fa-solid fa-user-check"></i>
            </div>
        </div>

        <!-- Card 4: Pasien Standar -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Pasien Standar</p>
                <p class="text-2xl font-extrabold text-slate-900 mt-1">{{ number_format($stats['total_pasien_standar']) }}</p>
                <p class="text-[11px] text-indigo-600 font-semibold mt-1">{{ $stats['total_ps_tersedia'] }} Tersedia / Siap</p>
            </div>
            <div class="w-11 h-11 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-lg">
                <i class="fa-solid fa-hospital-user"></i>
            </div>
        </div>

        <!-- Card 5: Pengguna Sistem -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Pengguna & Staf</p>
                <p class="text-2xl font-extrabold text-slate-900 mt-1">{{ number_format($stats['total_users']) }}</p>
                <p class="text-[11px] text-purple-600 font-semibold mt-1">Spatie Permission</p>
            </div>
            <div class="w-11 h-11 rounded-2xl bg-purple-50 text-purple-600 flex items-center justify-center text-lg">
                <i class="fa-solid fa-users-gear"></i>
            </div>
        </div>
    </div>

    @if($stats['total_trash'] > 0)
        <!-- Alert Kotak Sampah -->
        <div class="bg-rose-50 border border-rose-200 rounded-2xl p-4 flex items-center justify-between">
            <div class="flex items-center space-x-3 text-xs text-rose-800">
                <div class="w-8 h-8 rounded-xl bg-rose-100 flex items-center justify-center text-rose-600 font-bold">
                    <i class="fa-solid fa-trash-can"></i>
                </div>
                <div>
                    <span class="font-bold">Kotak Sampah (Trash) Berisi {{ $stats['total_trash'] }} Data:</span>
                    <span class="text-rose-600"> Terdapat data yang telah dihapus sementara (soft delete) dan tersimpan di tempat sampah.</span>
                </div>
            </div>
            <a href="{{ route('admin.trash.index') }}" class="px-3 py-1.5 bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold rounded-lg shadow-sm transition">
                Buka Kotak Sampah &rarr;
            </a>
        </div>
    @endif

    <!-- Quick Actions Header -->
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div>
            <h3 class="text-base font-bold text-slate-900">Aksi Cepat Tambah Data</h3>
            <p class="text-xs text-slate-500">Pilih modul yang ingin ditambahkan data barunya</p>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('admin.dosen.create') }}" class="inline-flex items-center space-x-2 px-3.5 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold rounded-xl shadow-sm transition">
                <i class="fa-solid fa-plus text-[10px]"></i>
                <span>Tambah Dosen</span>
            </a>
            <a href="{{ route('admin.eksternal.create') }}" class="inline-flex items-center space-x-2 px-3.5 py-2 bg-cyan-600 hover:bg-cyan-700 text-white text-xs font-semibold rounded-xl shadow-sm transition">
                <i class="fa-solid fa-plus text-[10px]"></i>
                <span>Tambah Eksternal</span>
            </a>
            <a href="{{ route('admin.penguji.create') }}" class="inline-flex items-center space-x-2 px-3.5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold rounded-xl shadow-sm transition">
                <i class="fa-solid fa-plus text-[10px]"></i>
                <span>Tambah Penguji</span>
            </a>
            <a href="{{ route('admin.pasien-standar.create') }}" class="inline-flex items-center space-x-2 px-3.5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold rounded-xl shadow-sm transition">
                <i class="fa-solid fa-plus text-[10px]"></i>
                <span>Tambah Pasien Standar</span>
            </a>
        </div>
    </div>

    <!-- Recent Tables Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Table 1: Dosen Terbaru -->
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-5">
            <div class="flex items-center justify-between mb-4">
                <h4 class="text-sm font-bold text-slate-800 flex items-center">
                    <i class="fa-solid fa-chalkboard-user text-blue-600 mr-2"></i>
                    Dosen Terbaru
                </h4>
                <a href="{{ route('admin.dosen.index') }}" class="text-xs text-blue-600 hover:underline font-semibold">Semua &rarr;</a>
            </div>
            <div class="space-y-3 text-xs">
                @foreach($latestDosens as $d)
                    <div class="p-3 bg-slate-50 rounded-xl border border-slate-100 flex items-center justify-between">
                        <div>
                            <p class="font-bold text-slate-900 leading-tight">{{ $d->nama_lengkap }}</p>
                            <p class="text-slate-500 text-[11px] mt-0.5">{{ $d->bagian }} &bull; <span class="font-semibold text-blue-600">{{ $d->jafung }}</span></p>
                        </div>
                        <a href="{{ route('admin.dosen.edit', $d) }}" class="p-1.5 text-slate-400 hover:text-blue-600">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Table 2: Penguji Terbaru -->
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-5">
            <div class="flex items-center justify-between mb-4">
                <h4 class="text-sm font-bold text-slate-800 flex items-center">
                    <i class="fa-solid fa-user-check text-emerald-600 mr-2"></i>
                    Penguji Terbaru
                </h4>
                <a href="{{ route('admin.penguji.index') }}" class="text-xs text-emerald-600 hover:underline font-semibold">Semua &rarr;</a>
            </div>
            <div class="space-y-3 text-xs">
                @foreach($latestPengujis as $p)
                    <div class="p-3 bg-slate-50 rounded-xl border border-slate-100 flex items-center justify-between">
                        <div>
                            <p class="font-bold text-slate-900 leading-tight">{{ $p->nama_lengkap }}</p>
                            <p class="text-slate-500 text-[11px] mt-0.5">{{ $p->jenis_penguji }} &bull; <span class="text-emerald-700 font-semibold">{{ $p->spesialisasi }}</span></p>
                        </div>
                        <a href="{{ route('admin.penguji.edit', $p) }}" class="p-1.5 text-slate-400 hover:text-emerald-600">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Table 3: Pasien Standar Terbaru -->
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-5">
            <div class="flex items-center justify-between mb-4">
                <h4 class="text-sm font-bold text-slate-800 flex items-center">
                    <i class="fa-solid fa-hospital-user text-indigo-600 mr-2"></i>
                    Pasien Standar Terbaru
                </h4>
                <a href="{{ route('admin.pasien-standar.index') }}" class="text-xs text-indigo-600 hover:underline font-semibold">Semua &rarr;</a>
            </div>
            <div class="space-y-3 text-xs">
                @foreach($latestPasiens as $ps)
                    <div class="p-3 bg-slate-50 rounded-xl border border-slate-100 flex items-center justify-between">
                        <div>
                            <div class="flex items-center space-x-1.5">
                                <span class="font-mono text-[10px] font-bold text-indigo-600 bg-indigo-50 px-1 rounded">{{ $ps->kode_ps }}</span>
                                <p class="font-bold text-slate-900 leading-tight">{{ $ps->nama_lengkap }}</p>
                            </div>
                            <p class="text-slate-500 text-[11px] mt-0.5 truncate max-w-[170px]">{{ $ps->peran_dikuasai }}</p>
                        </div>
                        <a href="{{ route('admin.pasien-standar.edit', $ps) }}" class="p-1.5 text-slate-400 hover:text-indigo-600">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>

    </div>

</div>
@endsection

