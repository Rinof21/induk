@extends('layouts.public')

@section('title', 'Data Dosen PNS & Non-PNS - BKU Universitas Tanjungpura')

@section('content')
<div x-data="{ modalOpen: false, activeDosen: {} }">

    <!-- Header Section (Sesuai Screenshot UNTAN) -->
    <div class="mb-8">
        <h2 class="text-3xl font-normal text-slate-900 tracking-tight">Data Dosen Internal Fakultas</h2>
        <p class="text-sm text-slate-500 mt-1">Daftar Dosen Internal Fakultas pada Bagian Kedokteran, Bagian Farmasi, dan Bagian Keperawatan</p>
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

                <div class="bg-slate-50 px-6 py-4 flex justify-end">
                    <button type="button" @click="modalOpen = false" class="px-4 py-2 bg-slate-800 hover:bg-slate-900 text-white text-xs font-semibold rounded-lg transition">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

