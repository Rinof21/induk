@extends('layouts.public')

@section('title', 'Data Dosen PNS & Non-PNS - BKU Universitas Tanjungpura')

@section('content')
<div x-data="{ 
    modalOpen: false, 
    activeDosen: {},
    ajukanModal: false,
    formAjukan: {
        record_id: '',
        nama_pengaju: '',
        email_pengaju: '',
        no_hp_pengaju: '',
        alasan_perubahan: '',
        data: {}
    },
    openAjukan(dosen) {
        this.formAjukan.record_id = dosen ? dosen.id : '';
        this.formAjukan.nama_pengaju = dosen ? dosen.nama_lengkap : '';
        this.formAjukan.email_pengaju = dosen ? (dosen.email || '') : '';
        this.formAjukan.no_hp_pengaju = dosen ? (dosen.no_hp || '') : '';
        this.formAjukan.alasan_perubahan = '';
        this.formAjukan.data = {
            nama_lengkap: dosen ? dosen.nama_lengkap : '',
            nip: dosen ? (dosen.nip || '') : '',
            nuptk: dosen ? (dosen.nuptk || '') : '',
            nik: dosen ? (dosen.nik || '') : '',
            npwp: dosen ? (dosen.npwp || '') : '',
            bagian: dosen ? (dosen.bagian || 'Bagian Kedokteran') : 'Bagian Kedokteran',
            pangkat_gol: dosen ? (dosen.pangkat_gol || '') : '',
            jafung: dosen ? (dosen.jafung || 'Tenaga Pengajar') : 'Tenaga Pengajar',
            pendidikan_terakhir: dosen ? (dosen.pendidikan_terakhir || 'S2') : 'S2',
            status_ikatan_kerja: dosen ? (dosen.status_ikatan_kerja || 'Dosen PNS') : 'Dosen PNS',
            status_tugas: dosen ? (dosen.status_tugas || 'Aktif') : 'Aktif',
            email: dosen ? (dosen.email || '') : '',
            no_hp: dosen ? (dosen.no_hp || '') : '',
            bidang_keahlian: dosen ? (dosen.bidang_keahlian || '') : '',
        };
        this.ajukanModal = true;
    }
}">

    <!-- Header Section (Sesuai Screenshot UNTAN) -->
    <div class="mb-8 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-3xl font-normal text-slate-900 tracking-tight">Data Dosen Internal Fakultas</h2>
            <p class="text-sm text-slate-500 mt-1">Daftar Dosen Internal Fakultas pada Bagian Kedokteran, Bagian Farmasi, dan Bagian Keperawatan</p>
        </div>
        <div>
            <button type="button" 
                    @click="openAjukan(null)" 
                    class="inline-flex items-center space-x-2 px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold rounded-xl shadow-md shadow-blue-600/20 transition">
                <i class="fa-solid fa-pen-to-square text-xs"></i>
                <span>Ajukan Perubahan Data</span>
            </button>
        </div>
    </div>

    <!-- Kotak Pencarian Utama (Gaya Banner Search UNTAN) -->
    <div class="bg-white p-4 sm:p-5 rounded-2xl shadow-sm border border-slate-200/80 mb-8">
        <form method="GET" action="{{ route('publik.dosen') }}" class="space-y-4">
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <i class="fa-solid fa-magnifying-glass text-slate-400"></i>
                </div>
                <input type="text" name="cari" value="{{ request('cari') }}"
                       placeholder="Cari berdasarkan NIP, Nama, Bagian, NUPTK, Golongan, atau Jafung..." 
                       class="w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white transition">
            </div>

            <!-- Filter Tambahan Bagian & Jafung -->
            <div class="flex flex-wrap items-center gap-3 pt-1">
                <div class="flex items-center space-x-2 text-sm font-semibold text-slate-500 mr-2">
                    <i class="fa-solid fa-filter"></i>
                    <span>Filter:</span>
                </div>

                <!-- Filter Bagian -->
                <select name="bagian" onchange="this.form.submit()" class="text-sm bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-slate-700 font-medium focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Semua Bagian</option>
                    @foreach($daftarBagian as $b)
                        <option value="{{ $b }}" {{ request('bagian') == $b ? 'selected' : '' }}>{{ $b }}</option>
                    @endforeach
                </select>

                <!-- Filter Jafung -->
                <select name="jafung" onchange="this.form.submit()" class="text-sm bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Semua Jabatan Fungsional</option>
                    @foreach($daftarJafung as $jf)
                        <option value="{{ $jf }}" {{ request('jafung') == $jf ? 'selected' : '' }}>{{ $jf }}</option>
                    @endforeach
                </select>

                <!-- Filter Status Tugas -->
                <select name="status_tugas" onchange="this.form.submit()" class="text-sm bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Semua Status</option>
                    <option value="Aktif" {{ request('status_tugas') == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                    <option value="Tugas Belajar" {{ request('status_tugas') == 'Tugas Belajar' ? 'selected' : '' }}>Tugas Belajar</option>
                    <option value="Izin Belajar" {{ request('status_tugas') == 'Izin Belajar' ? 'selected' : '' }}>Izin Belajar</option>
                </select>

                <button type="submit" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-xl shadow-sm transition">
                    Terapkan
                </button>

                @if(request()->anyFilled(['cari', 'bagian', 'jafung', 'status_tugas']))
                    <a href="{{ route('publik.dosen') }}" class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-600 text-sm font-medium rounded-xl transition">
                        Reset Filter
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- 4 Metric Cards (Persis seperti layout screenshot) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
        <!-- Card 1: Jumlah Dosen -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm">
            <p class="text-xs font-bold uppercase tracking-wider text-slate-400">JUMLAH DOSEN</p>
            <p class="text-4xl font-extrabold text-blue-600 mt-2">{{ number_format($stats['total_dosen']) }}</p>
        </div>

        <!-- Card 2: Jumlah Guru Besar -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm">
            <p class="text-xs font-bold uppercase tracking-wider text-slate-400">JUMLAH GURU BESAR</p>
            <p class="text-4xl font-extrabold text-blue-600 mt-2">{{ number_format($stats['total_guru_besar']) }}</p>
        </div>

        <!-- Card 3: Jumlah Dosen Tugas Belajar -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm">
            <p class="text-xs font-bold uppercase tracking-wider text-slate-400">JUMLAH DOSEN TUGAS BELAJAR</p>
            <p class="text-4xl font-extrabold text-amber-500 mt-2">{{ number_format($stats['total_tugas_belajar']) }}</p>
        </div>

        <!-- Card 4: Pembaruan Terakhir -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm">
            <p class="text-xs font-bold uppercase tracking-wider text-slate-400">PEMBARUAN TERAKHIR</p>
            <p class="text-sm font-semibold text-slate-700 mt-4 flex items-center">
                <i class="fa-regular fa-clock text-slate-400 mr-2"></i>
                {{ $stats['pembaruan_terakhir'] }}
            </p>
        </div>
    </div>

    <!-- Tabel Data Dosen -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-sm">
                <thead>
                    <tr class="bg-slate-50/90 border-b border-slate-200 text-slate-600 font-normal text-xs uppercase tracking-wider">
                        <th class="py-4 px-4 text-center w-14 font-normal">NO</th>
                        <th class="py-4 px-4 font-normal">NAMA LENGKAP</th>
                        <th class="py-4 px-4 font-normal">NIP / NUPTK</th>
                        <th class="py-4 px-4 font-normal">NIK / NPWP</th>
                        <th class="py-4 px-4 font-normal">PANGKAT/GOL</th>
                        <th class="py-4 px-4 font-normal">JAFUNG</th>
                        <th class="py-4 px-4 text-center font-normal">PENDIDIKAN</th>
                        <th class="py-4 px-4 font-normal">BAGIAN</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700 font-normal">
                    @forelse($dosens as $index => $dosen)
                        <tr class="hover:bg-blue-50/40 transition">
                            <td class="py-4 px-4 text-center text-slate-500 font-normal text-sm">
                                {{ $dosens->firstItem() + $index }}
                            </td>
                            <td class="py-4 px-4 text-slate-800 font-normal text-[15px]">
                                <a href="javascript:void(0)" 
                                   @click="activeDosen = {{ json_encode($dosen) }}; modalOpen = true"
                                   class="hover:text-blue-600 transition leading-snug font-normal text-slate-800">
                                    {{ $dosen->nama_lengkap }}
                                </a>
                                @if($dosen->status_tugas === 'Tugas Belajar')
                                    <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-xs font-normal bg-amber-50 text-amber-700 border border-amber-200">
                                        Tugas Belajar
                                    </span>
                                @endif
                            </td>
                            <td class="py-4 px-4 text-slate-700 font-normal text-[14px]">
                                <div>{{ $dosen->nip ?? '-' }}</div>
                                <div class="text-slate-400 text-xs">{{ $dosen->nuptk ? 'NUPTK: '.$dosen->nuptk : '' }}</div>
                            </td>
                            <td class="py-4 px-4 text-slate-700 font-normal text-[14px]">
                                <div>NIK: {{ $dosen->nik ?? '-' }}</div>
                                <div class="text-slate-400 text-xs">{{ $dosen->npwp ? 'NPWP: '.$dosen->npwp : '' }}</div>
                            </td>
                            <td class="py-4 px-4">
                                @if($dosen->pangkat_gol)
                                    <span class="inline-block px-3 py-1 rounded-lg text-xs font-normal bg-blue-50 text-blue-700 border border-blue-200">
                                        {{ $dosen->pangkat_gol }}
                                    </span>
                                @else
                                    <span class="text-slate-400 text-sm font-normal">-</span>
                                @endif
                            </td>
                            <td class="py-4 px-4 text-slate-700 font-normal text-[14px]">
                                {{ $dosen->jafung }}
                            </td>
                            <td class="py-4 px-4 text-center">
                                <span class="inline-block px-2.5 py-1 rounded-lg text-xs font-normal bg-slate-100 text-slate-700 border border-slate-200">
                                    {{ $dosen->pendidikan_terakhir }}
                                </span>
                            </td>
                            <td class="py-4 px-4">
                                <span class="inline-block px-3 py-1 rounded-lg text-xs font-normal {{ $dosen->bagian === 'Bagian Kedokteran' ? 'bg-blue-50 text-blue-700 border border-blue-200' : ($dosen->bagian === 'Bagian Farmasi' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-purple-50 text-purple-700 border border-purple-200') }}">
                                    {{ $dosen->bagian }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-12 text-center text-slate-400">
                                <i class="fa-solid fa-folder-open text-3xl mb-3 text-slate-300"></i>
                                <p class="text-sm">Tidak ditemukan data dosen yang cocok dengan kriteria pencarian.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($dosens->hasPages())
            <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/50">
                {{ $dosens->links() }}
            </div>
        @endif
    </div>

    <!-- Modal Detail Dosen -->
    <div x-show="modalOpen" 
         x-cloak
         class="fixed inset-0 z-50 overflow-y-auto"
         aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <!-- Backdrop -->
            <div x-show="modalOpen" 
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 @click="modalOpen = false"
                 class="fixed inset-0 bg-slate-900/60 transition-opacity"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <!-- Modal Card -->
            <div x-show="modalOpen" 
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-xl w-full">
                
                <div class="bg-gradient-to-r from-blue-600 to-indigo-700 px-6 py-5 text-white">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 rounded-xl bg-white/10 backdrop-blur flex items-center justify-center font-bold">
                                <i class="fa-solid fa-id-card text-lg"></i>
                            </div>
                            <div>
                                <h3 class="text-base font-bold leading-tight" x-text="activeDosen.nama_lengkap"></h3>
                                <p class="text-xs text-blue-100 font-medium" x-text="activeDosen.bagian"></p>
                            </div>
                        </div>
                        <button @click="modalOpen = false" class="text-white/80 hover:text-white text-xl font-bold">&times;</button>
                    </div>
                </div>

                <div class="p-6 space-y-4 text-xs">
                    <div class="grid grid-cols-2 gap-4">
                        <div class="bg-slate-50 p-3 rounded-xl border border-slate-100">
                            <span class="text-slate-400 font-semibold block text-[10px] uppercase">NIP</span>
                            <span class="text-slate-800 font-mono font-bold" x-text="activeDosen.nip || '-'"></span>
                        </div>
                        <div class="bg-slate-50 p-3 rounded-xl border border-slate-100">
                            <span class="text-slate-400 font-semibold block text-[10px] uppercase">NUPTK / NIDN</span>
                            <span class="text-slate-800 font-mono font-bold" x-text="activeDosen.nuptk || '-'"></span>
                        </div>
                        <div class="bg-slate-50 p-3 rounded-xl border border-slate-100">
                            <span class="text-slate-400 font-semibold block text-[10px] uppercase">Pangkat / Golongan</span>
                            <span class="text-slate-800 font-bold" x-text="activeDosen.pangkat_gol || '-'"></span>
                        </div>
                        <div class="bg-slate-50 p-3 rounded-xl border border-slate-100">
                            <span class="text-slate-400 font-semibold block text-[10px] uppercase">Jabatan Fungsional</span>
                            <span class="text-slate-800 font-bold" x-text="activeDosen.jafung"></span>
                        </div>
                        <div class="bg-slate-50 p-3 rounded-xl border border-slate-100">
                            <span class="text-slate-400 font-semibold block text-[10px] uppercase">Pendidikan Terakhir</span>
                            <span class="text-slate-800 font-bold" x-text="activeDosen.pendidikan_terakhir"></span>
                        </div>
                        <div class="bg-slate-50 p-3 rounded-xl border border-slate-100">
                            <span class="text-slate-400 font-semibold block text-[10px] uppercase">Status Ikatan Kerja</span>
                            <span class="text-slate-800 font-bold" x-text="activeDosen.status_ikatan_kerja"></span>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="bg-slate-50 p-3 rounded-xl border border-slate-100">
                            <span class="text-slate-400 font-semibold block text-[10px] uppercase">NIK (Kependudukan)</span>
                            <span class="text-slate-800 font-mono font-bold" x-text="activeDosen.nik || '-'"></span>
                        </div>
                        <div class="bg-slate-50 p-3 rounded-xl border border-slate-100">
                            <span class="text-slate-400 font-semibold block text-[10px] uppercase">NPWP (Pajak)</span>
                            <span class="text-slate-800 font-mono font-bold" x-text="activeDosen.npwp || '-'"></span>
                        </div>
                    </div>

                    <div class="bg-slate-50 p-3 rounded-xl border border-slate-100">
                        <span class="text-slate-400 font-semibold block text-[10px] uppercase">Bidang Keahlian / Kepakaran</span>
                        <span class="text-slate-800 font-medium" x-text="activeDosen.bidang_keahlian || 'Belum diisi'"></span>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="bg-slate-50 p-3 rounded-xl border border-slate-100">
                            <span class="text-slate-400 font-semibold block text-[10px] uppercase">Email</span>
                            <span class="text-slate-800 font-medium" x-text="activeDosen.email || '-'"></span>
                        </div>
                        <div class="bg-slate-50 p-3 rounded-xl border border-slate-100">
                            <span class="text-slate-400 font-semibold block text-[10px] uppercase">Kontak / No. HP</span>
                            <span class="text-slate-800 font-medium" x-text="activeDosen.no_hp || '-'"></span>
                        </div>
                    </div>
                </div>

                <div class="bg-slate-50 px-6 py-4 flex flex-col sm:flex-row items-center justify-between gap-3">
                    <button type="button" 
                            @click="modalOpen = false; openAjukan(activeDosen)" 
                            class="w-full sm:w-auto inline-flex items-center justify-center space-x-1.5 px-4 py-2 bg-blue-50 hover:bg-blue-100 text-blue-700 text-xs font-semibold rounded-lg border border-blue-200 transition">
                        <i class="fa-solid fa-pen-to-square text-xs"></i>
                        <span>Ajukan Perubahan Data Ini</span>
                    </button>
                    <button type="button" @click="modalOpen = false" class="w-full sm:w-auto px-4 py-2 bg-slate-800 hover:bg-slate-900 text-white text-xs font-semibold rounded-lg transition">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Form Pengajuan Perubahan Data Dosen -->
    <div x-show="ajukanModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen p-4 text-center">
            <div x-show="ajukanModal" @click="ajukanModal = false" class="fixed inset-0 bg-slate-900/60 transition-opacity"></div>
            
            <div class="bg-white rounded-2xl max-w-2xl w-full p-6 text-left shadow-2xl relative z-10 max-h-[90vh] overflow-y-auto">
                <!-- Modal Header -->
                <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-5">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-lg">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </div>
                        <div>
                            <h4 class="text-base font-bold text-slate-900">Form Pengajuan Perubahan Data Dosen</h4>
                            <p class="text-xs text-slate-500">Ajukan permohonan koreksi atau pembaruan profil ke Administrator Fakultas</p>
                        </div>
                    </div>
                    <button type="button" @click="ajukanModal = false" class="text-slate-400 hover:text-slate-600 p-1 rounded-lg">
                        <i class="fa-solid fa-xmark text-lg"></i>
                    </button>
                </div>

                <!-- Form -->
                <form action="{{ route('publik.ajukan-perubahan') }}" method="POST" enctype="multipart/form-data" class="space-y-4 text-xs">
                    @csrf
                    <input type="hidden" name="tipe" value="dosen">
                    <input type="hidden" name="record_id" :value="formAjukan.record_id">

                    <!-- Section 1: Identitas Pemohon -->
                    <div class="p-4 bg-blue-50/60 border border-blue-100 rounded-xl space-y-3">
                        <p class="font-bold text-blue-900 text-[11px] uppercase tracking-wider flex items-center">
                            <i class="fa-solid fa-id-badge mr-1.5"></i> Identitas Pemohon Perubahan
                        </p>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                            <div class="sm:col-span-1">
                                <label class="block font-semibold text-slate-700 mb-1">Nama Pemohon (*)</label>
                                <input type="text" name="nama_pengaju" x-model="formAjukan.nama_pengaju" required
                                       placeholder="Nama Lengkap Anda"
                                       class="w-full px-3 py-2 bg-white border border-slate-200 rounded-lg text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                            <div>
                                <label class="block font-semibold text-slate-700 mb-1">Email Pemohon</label>
                                <input type="email" name="email_pengaju" x-model="formAjukan.email_pengaju"
                                       placeholder="email@untan.ac.id"
                                       class="w-full px-3 py-2 bg-white border border-slate-200 rounded-lg text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                            <div>
                                <label class="block font-semibold text-slate-700 mb-1">No. WhatsApp / HP</label>
                                <input type="text" name="no_hp_pengaju" x-model="formAjukan.no_hp_pengaju"
                                       placeholder="08123456789"
                                       class="w-full px-3 py-2 bg-white border border-slate-200 rounded-lg text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                        </div>

                        <div>
                            <label class="block font-semibold text-slate-700 mb-1">Alasan / Catatan Perubahan (Opsional)</label>
                            <textarea name="alasan_perubahan" x-model="formAjukan.alasan_perubahan" rows="2"
                                      placeholder="Contoh: Pembaruan ijazah S3 yang baru lulus, kenaikan jenjang jabatan fungsional Lektor Kepala, atau perubahan NIK/NPWP..."
                                      class="w-full px-3 py-2 bg-white border border-slate-200 rounded-lg text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                        </div>

                        <div>
                            <label class="block font-semibold text-slate-700 mb-1">Dokumen Pendukung / SK (Opsional, PDF/JPG maks 5MB)</label>
                            <input type="file" name="dokumen" accept=".pdf,.jpg,.jpeg,.png"
                                   class="w-full text-xs text-slate-600 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-blue-600 file:text-white hover:file:bg-blue-700 cursor-pointer">
                        </div>
                    </div>

                    <!-- Section 2: Data Dosen yang Diperbarui -->
                    <div class="p-4 bg-slate-50 border border-slate-200 rounded-xl space-y-3">
                        <p class="font-bold text-slate-800 text-[11px] uppercase tracking-wider flex items-center">
                            <i class="fa-solid fa-chalkboard-user mr-1.5 text-blue-600"></i> Usulan Data Baru Dosen
                        </p>
                        
                        <div>
                            <label class="block font-semibold text-slate-700 mb-1">Nama Lengkap (beserta gelar) (*)</label>
                            <input type="text" name="data_baru[nama_lengkap]" x-model="formAjukan.data.nama_lengkap" required
                                   class="w-full px-3 py-2 bg-white border border-slate-200 rounded-lg text-slate-800 font-semibold focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div>
                                <label class="block font-semibold text-slate-700 mb-1">NIP</label>
                                <input type="text" name="data_baru[nip]" x-model="formAjukan.data.nip"
                                       class="w-full px-3 py-2 bg-white border border-slate-200 rounded-lg text-slate-800 font-mono focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                            <div>
                                <label class="block font-semibold text-slate-700 mb-1">NUPTK / NIDN</label>
                                <input type="text" name="data_baru[nuptk]" x-model="formAjukan.data.nuptk"
                                       class="w-full px-3 py-2 bg-white border border-slate-200 rounded-lg text-slate-800 font-mono focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div>
                                <label class="block font-semibold text-slate-700 mb-1">NIK (KTP)</label>
                                <input type="text" name="data_baru[nik]" x-model="formAjukan.data.nik"
                                       class="w-full px-3 py-2 bg-white border border-slate-200 rounded-lg text-slate-800 font-mono focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                            <div>
                                <label class="block font-semibold text-slate-700 mb-1">NPWP</label>
                                <input type="text" name="data_baru[npwp]" x-model="formAjukan.data.npwp"
                                       class="w-full px-3 py-2 bg-white border border-slate-200 rounded-lg text-slate-800 font-mono focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div>
                                <label class="block font-semibold text-slate-700 mb-1">Bagian Internal (*)</label>
                                <select name="data_baru[bagian]" x-model="formAjukan.data.bagian" required
                                        class="w-full px-3 py-2 bg-white border border-slate-200 rounded-lg text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    @foreach($daftarBagian as $b)
                                        <option value="{{ $b }}">{{ $b }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block font-semibold text-slate-700 mb-1">Jabatan Fungsional (*)</label>
                                <select name="data_baru[jafung]" x-model="formAjukan.data.jafung" required
                                        class="w-full px-3 py-2 bg-white border border-slate-200 rounded-lg text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    @foreach($daftarJafung as $jf)
                                        <option value="{{ $jf }}">{{ $jf }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div>
                                <label class="block font-semibold text-slate-700 mb-1">Pangkat / Golongan</label>
                                <input type="text" name="data_baru[pangkat_gol]" x-model="formAjukan.data.pangkat_gol"
                                       placeholder="Contoh: Pembina / IV/a"
                                       class="w-full px-3 py-2 bg-white border border-slate-200 rounded-lg text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                            <div>
                                <label class="block font-semibold text-slate-700 mb-1">Pendidikan Terakhir (*)</label>
                                <select name="data_baru[pendidikan_terakhir]" x-model="formAjukan.data.pendidikan_terakhir" required
                                        class="w-full px-3 py-2 bg-white border border-slate-200 rounded-lg text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    @foreach(['S1', 'S2', 'S3', 'Sp-1', 'Sp-2', 'Subspesialis'] as $p)
                                        <option value="{{ $p }}">{{ $p }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div>
                                <label class="block font-semibold text-slate-700 mb-1">Status Ikatan Kerja</label>
                                <select name="data_baru[status_ikatan_kerja]" x-model="formAjukan.data.status_ikatan_kerja"
                                        class="w-full px-3 py-2 bg-white border border-slate-200 rounded-lg text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <option value="Dosen PNS">Dosen PNS</option>
                                    <option value="Dosen PPPK">Dosen PPPK</option>
                                    <option value="Dosen Tetap Non-PNS">Dosen Tetap Non-PNS</option>
                                    <option value="Dosen Kontrak">Dosen Kontrak</option>
                                </select>
                            </div>
                            <div>
                                <label class="block font-semibold text-slate-700 mb-1">Status Keaktifan / Tugas</label>
                                <select name="data_baru[status_tugas]" x-model="formAjukan.data.status_tugas"
                                        class="w-full px-3 py-2 bg-white border border-slate-200 rounded-lg text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <option value="Aktif">Aktif</option>
                                    <option value="Tugas Belajar">Tugas Belajar</option>
                                    <option value="Izin Belajar">Izin Belajar</option>
                                </select>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div>
                                <label class="block font-semibold text-slate-700 mb-1">Email Resmi</label>
                                <input type="email" name="data_baru[email]" x-model="formAjukan.data.email"
                                       class="w-full px-3 py-2 bg-white border border-slate-200 rounded-lg text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                            <div>
                                <label class="block font-semibold text-slate-700 mb-1">No. HP / WhatsApp Dosen</label>
                                <input type="text" name="data_baru[no_hp]" x-model="formAjukan.data.no_hp"
                                       class="w-full px-3 py-2 bg-white border border-slate-200 rounded-lg text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                        </div>

                        <div>
                            <label class="block font-semibold text-slate-700 mb-1">Bidang Keahlian / Minat Riset</label>
                            <input type="text" name="data_baru[bidang_keahlian]" x-model="formAjukan.data.bidang_keahlian"
                                   placeholder="Contoh: Farmakologi Molekuler, Bedah Anak, Pediatri..."
                                   class="w-full px-3 py-2 bg-white border border-slate-200 rounded-lg text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex items-center space-x-3 pt-3">
                        <button type="button" @click="ajukanModal = false" class="flex-1 py-2.5 px-4 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold rounded-xl transition">
                            Batal
                        </button>
                        <button type="submit" class="flex-1 py-2.5 px-4 bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold rounded-xl shadow-md shadow-blue-600/30 transition inline-flex items-center justify-center space-x-1.5">
                            <i class="fa-solid fa-paper-plane text-xs"></i>
                            <span>Kirim Pengajuan</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>
@endsection

