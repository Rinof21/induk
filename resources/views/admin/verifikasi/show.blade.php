@extends('layouts.admin')

@section('title', 'Tinjau Pengajuan Perubahan Data')
@section('page_title', 'Tinjau Pengajuan Perubahan Data')

@section('content')
<div class="space-y-6" x-data="{ approveModal: false, rejectModal: false }">

    <!-- Back & Breadcrumb Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <a href="{{ route('admin.verifikasi.index') }}" class="inline-flex items-center space-x-1.5 text-xs text-blue-600 hover:text-blue-800 font-semibold mb-2">
                <i class="fa-solid fa-arrow-left"></i>
                <span>Kembali ke Daftar Verifikasi</span>
            </a>
            <h3 class="text-xl font-bold text-slate-900">Tinjau Pengajuan: {{ $pengajuan->target_name }}</h3>
            <p class="text-xs text-slate-500">Bandingkan perubahan nilai lama dengan usulan baru sebelum menerapkan ke data utama</p>
        </div>

        <!-- Action Buttons if Pending -->
        @if($pengajuan->status === 'pending')
            <div class="flex items-center space-x-2">
                <button type="button" @click="rejectModal = true" 
                        class="inline-flex items-center space-x-1.5 px-4 py-2.5 bg-rose-50 hover:bg-rose-100 text-rose-700 text-xs font-bold rounded-xl border border-rose-200 transition">
                    <i class="fa-solid fa-xmark text-xs"></i>
                    <span>Tolak Pengajuan</span>
                </button>
                <button type="button" @click="approveModal = true" 
                        class="inline-flex items-center space-x-1.5 px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl shadow-md shadow-emerald-600/30 transition">
                    <i class="fa-solid fa-check text-xs"></i>
                    <span>Setujui & Terapkan Data</span>
                </button>
            </div>
        @else
            <div>
                @if($pengajuan->status === 'approved')
                    <span class="inline-flex items-center px-3 py-1.5 rounded-xl text-xs font-bold bg-emerald-100 text-emerald-800 border border-emerald-200">
                        <i class="fa-solid fa-circle-check mr-1.5"></i> Telah Disetujui (Data Diperbarui)
                    </span>
                @else
                    <span class="inline-flex items-center px-3 py-1.5 rounded-xl text-xs font-bold bg-rose-100 text-rose-800 border border-rose-200">
                        <i class="fa-solid fa-circle-xmark mr-1.5"></i> Telah Ditolak
                    </span>
                @endif
            </div>
        @endif
    </div>

    <!-- Info Pengaju Card -->
    <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm">
        <h4 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-4 flex items-center">
            <i class="fa-solid fa-user-pen mr-2 text-blue-600"></i>
            Informasi Pemohon & Dokumen Pendukung
        </h4>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-xs">
            <!-- Col 1: Identitas Pemohon -->
            <div class="space-y-3 bg-slate-50 p-4 rounded-xl border border-slate-100">
                <div>
                    <span class="text-slate-400 block text-[10px] uppercase font-bold">Nama Pemohon</span>
                    <span class="text-slate-900 font-bold text-sm">{{ $pengajuan->nama_pengaju }}</span>
                </div>
                <div>
                    <span class="text-slate-400 block text-[10px] uppercase font-bold">Email Pemohon</span>
                    <span class="text-slate-700 font-medium">{{ $pengajuan->email_pengaju ?: '-' }}</span>
                </div>
                <div>
                    <span class="text-slate-400 block text-[10px] uppercase font-bold">No. WhatsApp / HP</span>
                    <span class="text-slate-700 font-medium">{{ $pengajuan->no_hp_pengaju ?: '-' }}</span>
                </div>
                <div>
                    <span class="text-slate-400 block text-[10px] uppercase font-bold">Waktu Diajukan</span>
                    <span class="text-slate-700 font-mono">{{ $pengajuan->created_at->translatedFormat('d F Y, H:i') }} WIB</span>
                </div>
            </div>

            <!-- Col 2: Alasan Perubahan -->
            <div class="bg-slate-50 p-4 rounded-xl border border-slate-100 flex flex-col justify-between">
                <div>
                    <span class="text-slate-400 block text-[10px] uppercase font-bold mb-1">Alasan / Catatan Pengaju</span>
                    <p class="text-slate-700 leading-relaxed font-normal whitespace-pre-line">
                        {{ $pengajuan->alasan_perubahan ?: 'Tidak ada catatan atau alasan khusus yang dicantumkan pemohon.' }}
                    </p>
                </div>

                @if($pengajuan->dokumen_pendukung)
                    <div class="mt-4 pt-3 border-t border-slate-200">
                        <span class="text-slate-400 block text-[10px] uppercase font-bold mb-1">Dokumen / Bukti Pendukung</span>
                        <a href="{{ asset('storage/' . $pengajuan->dokumen_pendukung) }}" target="_blank" 
                           class="inline-flex items-center space-x-1.5 px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-xs font-semibold shadow-sm transition">
                            <i class="fa-solid fa-file-arrow-down text-xs"></i>
                            <span>Buka / Unduh Bukti Dokumen</span>
                        </a>
                    </div>
                @endif
            </div>

            <!-- Col 3: Riwayat Verifikasi Admin -->
            <div class="bg-slate-50 p-4 rounded-xl border border-slate-100">
                <span class="text-slate-400 block text-[10px] uppercase font-bold mb-2">Status & Hasil Verifikasi</span>
                @if($pengajuan->status === 'pending')
                    <div class="p-3 bg-amber-50 border border-amber-200 rounded-xl text-amber-800 space-y-1">
                        <p class="font-bold flex items-center">
                            <i class="fa-solid fa-hourglass-half mr-1.5"></i> Menunggu Keputusan
                        </p>
                        <p class="text-[11px] text-amber-700 font-normal">
                            Data master belum diubah. Klik tombol <strong>Setujui</strong> di atas untuk langsung menerapkan perubahan ke sistem.
                        </p>
                    </div>
                @else
                    <div class="space-y-2">
                        <div>
                            <span class="text-slate-400 block text-[10px]">Diverifikasi Oleh</span>
                            <span class="text-slate-800 font-semibold">{{ $pengajuan->verifier->name ?? 'Administrator' }}</span>
                        </div>
                        <div>
                            <span class="text-slate-400 block text-[10px]">Tanggal Verifikasi</span>
                            <span class="text-slate-800 font-mono">{{ $pengajuan->verified_at ? $pengajuan->verified_at->translatedFormat('d F Y, H:i') : '-' }} WIB</span>
                        </div>
                        @if($pengajuan->catatan_admin)
                            <div>
                                <span class="text-slate-400 block text-[10px]">Catatan Admin</span>
                                <span class="text-slate-700 font-normal italic">"{{ $pengajuan->catatan_admin }}"</span>
                            </div>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Tabel Komparasi Nilai Lama vs Nilai Baru -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h4 class="text-sm font-bold text-slate-900">Perbandingan Data (Lama vs Diajukan)</h4>
                <p class="text-xs text-slate-500">Baris dengan latar belakang hijau muda menandakan terdapat perbedaan / pembaruan data</p>
            </div>
            <div class="flex items-center space-x-3 text-xs">
                <span class="flex items-center text-slate-500">
                    <span class="w-3 h-3 rounded bg-slate-100 border border-slate-200 mr-1.5"></span> Tetap (Sama)
                </span>
                <span class="flex items-center text-emerald-700 font-semibold">
                    <span class="w-3 h-3 rounded bg-emerald-100 border border-emerald-300 mr-1.5"></span> Diperbarui
                </span>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-slate-500 font-bold uppercase tracking-wider">
                        <th class="py-3.5 px-4 w-1/4">ATRIBUT / FIELD</th>
                        <th class="py-3.5 px-4 w-1/3">DATA SAAT INI (LAMA)</th>
                        <th class="py-3.5 px-4 w-1/3">DATA YANG DIAJUKAN (BARU)</th>
                        <th class="py-3.5 px-4 text-center w-28">STATUS</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @foreach($diff as $key => $item)
                        <tr class="{{ $item['changed'] ? 'bg-emerald-50/40 hover:bg-emerald-50/70' : 'hover:bg-slate-50' }} transition">
                            <td class="py-3.5 px-4 font-bold text-slate-800">
                                {{ $item['label'] }}
                                <span class="block text-[10px] text-slate-400 font-mono font-normal">{{ $key }}</span>
                            </td>
                            <td class="py-3.5 px-4 font-normal text-slate-600">
                                {{ $item['old'] }}
                            </td>
                            <td class="py-3.5 px-4 font-medium {{ $item['changed'] ? 'text-emerald-900 font-bold bg-emerald-100/50 rounded-lg' : 'text-slate-600' }}">
                                {{ $item['new'] }}
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                @if($item['changed'])
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-100 text-emerald-800 border border-emerald-200">
                                        <i class="fa-solid fa-pen mr-1 text-[9px]"></i> Berubah
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-medium bg-slate-100 text-slate-500">
                                        Tetap
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Konfirmasi Persetujuan (Approve) -->
    <div x-show="approveModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen p-4 text-center">
            <div x-show="approveModal" @click="approveModal = false" class="fixed inset-0 bg-slate-900/60 transition-opacity"></div>
            
            <div class="bg-white rounded-2xl max-w-md w-full p-6 text-left shadow-2xl relative z-10">
                <div class="flex items-center space-x-3 mb-4">
                    <div class="w-10 h-10 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center text-lg">
                        <i class="fa-solid fa-circle-check"></i>
                    </div>
                    <div>
                        <h4 class="text-base font-bold text-slate-900">Setujui & Terapkan Perubahan?</h4>
                        <p class="text-xs text-slate-500">Data utama di sistem akan langsung diperbarui dengan data baru</p>
                    </div>
                </div>

                <form action="{{ route('admin.verifikasi.approve', $pengajuan) }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Catatan Persetujuan (Opsional)</label>
                        <textarea name="catatan_admin" rows="3" placeholder="Contoh: Perubahan gelar dan jabatan telah disesuaikan dengan SK Dekan..."
                                  class="w-full text-xs p-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-emerald-500"></textarea>
                    </div>

                    <div class="flex items-center space-x-3 pt-2">
                        <button type="button" @click="approveModal = false" class="flex-1 py-2.5 px-4 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold rounded-xl transition">
                            Batal
                        </button>
                        <button type="submit" class="flex-1 py-2.5 px-4 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold rounded-xl shadow-md shadow-emerald-600/30 transition">
                            Ya, Setujui Sekarang
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Konfirmasi Penolakan (Reject) -->
    <div x-show="rejectModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen p-4 text-center">
            <div x-show="rejectModal" @click="rejectModal = false" class="fixed inset-0 bg-slate-900/60 transition-opacity"></div>
            
            <div class="bg-white rounded-2xl max-w-md w-full p-6 text-left shadow-2xl relative z-10">
                <div class="flex items-center space-x-3 mb-4">
                    <div class="w-10 h-10 rounded-xl bg-rose-100 text-rose-600 flex items-center justify-center text-lg">
                        <i class="fa-solid fa-triangle-exclamation"></i>
                    </div>
                    <div>
                        <h4 class="text-base font-bold text-slate-900">Tolak Pengajuan Perubahan?</h4>
                        <p class="text-xs text-slate-500">Data utama tidak akan berubah. Berikan alasan penolakan.</p>
                    </div>
                </div>

                <form action="{{ route('admin.verifikasi.reject', $pengajuan) }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Alasan Penolakan (*Wajib diisi)</label>
                        <textarea name="catatan_admin" rows="3" required placeholder="Contoh: Dokumen pendukung belum melampirkan SK terbaru atau NIP tidak sesuai..."
                                  class="w-full text-xs p-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-rose-500"></textarea>
                    </div>

                    <div class="flex items-center space-x-3 pt-2">
                        <button type="button" @click="rejectModal = false" class="flex-1 py-2.5 px-4 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold rounded-xl transition">
                            Batal
                        </button>
                        <button type="submit" class="flex-1 py-2.5 px-4 bg-rose-600 hover:bg-rose-700 text-white text-xs font-semibold rounded-xl shadow-md shadow-rose-600/30 transition">
                            Tolak Pengajuan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>
@endsection
