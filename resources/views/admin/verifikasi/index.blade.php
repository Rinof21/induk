@extends('layouts.admin')

@section('title', 'Verifikasi Pengajuan Perubahan Data')
@section('page_title', 'Verifikasi Pengajuan Data')

@section('content')
<div class="space-y-6" x-data="{ deleteModal: false, deleteUrl: '' }">

    <!-- Top Action Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h3 class="text-xl font-bold text-slate-900">Verifikasi Perubahan Data Publik</h3>
            <p class="text-xs text-slate-500">Tinjau, setujui, atau tolak permohonan pembaruan data yang diajukan oleh pengunjung portal</p>
        </div>
    </div>

    <!-- Metric Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Total -->
        <a href="{{ route('admin.verifikasi.index', ['status' => '']) }}" 
           class="p-5 rounded-2xl bg-white border border-slate-200 shadow-sm hover:border-slate-300 transition {{ empty(request('status')) ? 'ring-2 ring-blue-500' : '' }}">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">TOTAL PENGAJUAN</p>
                    <p class="text-2xl font-black text-slate-800 mt-1">{{ number_format($stats['total']) }}</p>
                </div>
                <div class="w-10 h-10 rounded-xl bg-slate-100 text-slate-600 flex items-center justify-center text-lg">
                    <i class="fa-solid fa-list"></i>
                </div>
            </div>
        </a>

        <!-- Pending -->
        <a href="{{ route('admin.verifikasi.index', ['status' => 'pending']) }}" 
           class="p-5 rounded-2xl bg-white border border-slate-200 shadow-sm hover:border-amber-300 transition {{ request('status', 'pending') === 'pending' ? 'ring-2 ring-amber-500' : '' }}">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[11px] font-bold uppercase tracking-wider text-amber-600">MENUNGGU VERIFIKASI</p>
                    <p class="text-2xl font-black text-amber-600 mt-1">{{ number_format($stats['pending']) }}</p>
                </div>
                <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-lg">
                    <i class="fa-solid fa-clock"></i>
                </div>
            </div>
        </a>

        <!-- Approved -->
        <a href="{{ route('admin.verifikasi.index', ['status' => 'approved']) }}" 
           class="p-5 rounded-2xl bg-white border border-slate-200 shadow-sm hover:border-emerald-300 transition {{ request('status') === 'approved' ? 'ring-2 ring-emerald-500' : '' }}">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[11px] font-bold uppercase tracking-wider text-emerald-600">DISETUJUI</p>
                    <p class="text-2xl font-black text-emerald-600 mt-1">{{ number_format($stats['approved']) }}</p>
                </div>
                <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-lg">
                    <i class="fa-solid fa-circle-check"></i>
                </div>
            </div>
        </a>

        <!-- Rejected -->
        <a href="{{ route('admin.verifikasi.index', ['status' => 'rejected']) }}" 
           class="p-5 rounded-2xl bg-white border border-slate-200 shadow-sm hover:border-rose-300 transition {{ request('status') === 'rejected' ? 'ring-2 ring-rose-500' : '' }}">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[11px] font-bold uppercase tracking-wider text-rose-600">DITOLAK</p>
                    <p class="text-2xl font-black text-rose-600 mt-1">{{ number_format($stats['rejected']) }}</p>
                </div>
                <div class="w-10 h-10 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center text-lg">
                    <i class="fa-solid fa-circle-xmark"></i>
                </div>
            </div>
        </a>
    </div>

    <!-- Filter Card -->
    <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm">
        <form method="GET" action="{{ route('admin.verifikasi.index') }}" class="flex flex-wrap items-center gap-3">
            <input type="hidden" name="status" value="{{ request('status', 'pending') }}">

            <div class="relative flex-1 min-w-[240px]">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </div>
                <input type="text" name="cari" value="{{ request('cari') }}" 
                       placeholder="Cari pengaju, email, no hp, atau alasan..."
                       class="w-full pl-10 pr-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-600">
            </div>

            <select name="tipe" onchange="this.form.submit()" class="text-xs bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-slate-700">
                <option value="">Semua Tipe Data</option>
                <option value="dosen" {{ request('tipe') === 'dosen' ? 'selected' : '' }}>Dosen Internal</option>
                <option value="eksternal" {{ request('tipe') === 'eksternal' ? 'selected' : '' }}>Tenaga Eksternal</option>
            </select>

            <button type="submit" class="px-4 py-2 bg-slate-800 hover:bg-slate-900 text-white text-xs font-semibold rounded-xl transition">
                Cari
            </button>

            @if(request()->anyFilled(['cari', 'tipe']))
                <a href="{{ route('admin.verifikasi.index', ['status' => request('status', 'pending')]) }}" class="px-3 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 text-xs font-medium rounded-xl transition">
                    Reset Filter
                </a>
            @endif
        </form>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-slate-500 font-bold uppercase tracking-wider">
                        <th class="py-3.5 px-4 text-center w-12">NO</th>
                        <th class="py-3.5 px-4">TANGGAL PENGAJUAN</th>
                        <th class="py-3.5 px-4">TIPE DATA & TARGET</th>
                        <th class="py-3.5 px-4">PENGAJU (KONTAK)</th>
                        <th class="py-3.5 px-4">ALASAN / CATATAN</th>
                        <th class="py-3.5 px-4 text-center">DOKUMEN</th>
                        <th class="py-3.5 px-4 text-center">STATUS</th>
                        <th class="py-3.5 px-4 text-center w-36">AKSI</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700 font-medium">
                    @forelse($pengajuans as $index => $row)
                        <tr class="hover:bg-slate-50 transition">
                            <td class="py-3.5 px-4 text-center text-slate-400">
                                {{ $pengajuans->firstItem() + $index }}
                            </td>
                            <td class="py-3.5 px-4 font-mono text-[11px] text-slate-600">
                                <div>{{ $row->created_at->format('d/m/Y') }}</div>
                                <div class="text-slate-400 text-[10px]">{{ $row->created_at->format('H:i') }} WIB</div>
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="inline-block px-2 py-0.5 rounded text-[10px] font-bold mb-1 {{ $row->tipe === 'dosen' ? 'bg-blue-50 text-blue-700 border border-blue-200' : 'bg-purple-50 text-purple-700 border border-purple-200' }}">
                                    {{ $row->tipe === 'dosen' ? 'Dosen Internal' : 'Tenaga Eksternal' }}
                                </span>
                                <div class="font-bold text-slate-900 text-xs">
                                    {{ $row->target_name }}
                                </div>
                            </td>
                            <td class="py-3.5 px-4">
                                <div class="font-bold text-slate-800">{{ $row->nama_pengaju }}</div>
                                <div class="text-slate-400 text-[11px]">{{ $row->email_pengaju ?? '-' }}</div>
                                <div class="text-slate-400 text-[11px]">{{ $row->no_hp_pengaju ? 'HP: '.$row->no_hp_pengaju : '' }}</div>
                            </td>
                            <td class="py-3.5 px-4 max-w-xs">
                                <p class="line-clamp-2 text-slate-600 font-normal">
                                    {{ $row->alasan_perubahan ?: 'Tidak ada alasan khusus dicantumkan.' }}
                                </p>
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                @if($row->dokumen_pendukung)
                                    <a href="{{ asset('storage/' . $row->dokumen_pendukung') }}" target="_blank" 
                                       title="Buka Dokumen Pendukung"
                                       class="inline-flex items-center space-x-1 px-2.5 py-1 bg-blue-50 text-blue-700 hover:bg-blue-100 rounded-lg text-[11px] font-semibold border border-blue-200 transition">
                                        <i class="fa-solid fa-paperclip"></i>
                                        <span>Lihat</span>
                                    </a>
                                @else
                                    <span class="text-slate-400 text-[11px]">-</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                @if($row->status === 'pending')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold bg-amber-100 text-amber-800 border border-amber-200">
                                        <i class="fa-solid fa-clock mr-1"></i> Menunggu
                                    </span>
                                @elseif($row->status === 'approved')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800 border border-emerald-200">
                                        <i class="fa-solid fa-check mr-1"></i> Disetujui
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold bg-rose-100 text-rose-800 border border-rose-200">
                                        <i class="fa-solid fa-xmark mr-1"></i> Ditolak
                                    </span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                <div class="inline-flex items-center space-x-1.5">
                                    <a href="{{ route('admin.verifikasi.show', $row) }}" 
                                       class="inline-flex items-center space-x-1 px-2.5 py-1.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-xs font-semibold shadow-sm transition">
                                        <i class="fa-solid fa-magnifying-glass text-[10px]"></i>
                                        <span>Tinjau</span>
                                    </a>
                                    <button type="button" 
                                            @click="deleteModal = true; deleteUrl = '{{ route('admin.verifikasi.destroy', $row) }}'"
                                            title="Hapus Riwayat"
                                            class="p-1.5 text-rose-600 hover:bg-rose-50 rounded-lg transition">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-12 text-center text-slate-400">
                                Belum ada pengajuan perubahan data pada status ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($pengajuans->hasPages())
            <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/50">
                {{ $pengajuans->links() }}
            </div>
        @endif
    </div>

    <!-- Modal Konfirmasi Hapus -->
    <div x-show="deleteModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen p-4 text-center">
            <div x-show="deleteModal" @click="deleteModal = false" class="fixed inset-0 bg-slate-900/60 transition-opacity"></div>
            
            <div class="bg-white rounded-2xl max-w-sm w-full p-6 text-center shadow-2xl relative z-10">
                <div class="w-12 h-12 rounded-full bg-rose-100 text-rose-600 flex items-center justify-center mx-auto mb-4 text-xl">
                    <i class="fa-solid fa-trash-can"></i>
                </div>
                <h4 class="text-base font-bold text-slate-900">Hapus Riwayat Pengajuan?</h4>
                <p class="text-xs text-slate-500 mt-2">
                    Apakah Anda yakin ingin menghapus riwayat pengajuan ini? Tindakan ini tidak dapat dibatalkan.
                </p>

                <div class="flex items-center space-x-3 mt-6">
                    <button type="button" @click="deleteModal = false" class="flex-1 py-2.5 px-4 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold rounded-xl transition">
                        Batal
                    </button>
                    <form :action="deleteUrl" method="POST" class="flex-1">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full py-2.5 px-4 bg-rose-600 hover:bg-rose-700 text-white text-xs font-semibold rounded-xl shadow-md shadow-rose-600/30 transition">
                            Hapus
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
