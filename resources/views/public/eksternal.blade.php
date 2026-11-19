@extends('layouts.public')

@section('title', 'Data Tenaga / Dosen Eksternal - BKU Universitas Tanjungpura')

@section('content')
<div x-data="{ modalOpen: false, activeRow: {} }">

    <!-- Header Section -->
    <div class="mb-8">
        <h2 class="text-3xl font-normal text-slate-900 tracking-tight">Data Tenaga / Dosen Eksternal</h2>
        <p class="text-sm text-slate-500 mt-1">Daftar Dosen Tamu, Pengajar Eksternal, dan Praktisi Ahli Mitra Rumah Sakit / Instansi Luar</p>
    </div>

    <!-- Kotak Pencarian Utama -->
    <div class="bg-white p-4 sm:p-5 rounded-2xl shadow-sm border border-slate-200/80 mb-8">
        <form method="GET" action="{{ route('publik.eksternal') }}" class="space-y-4">
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <i class="fa-solid fa-magnifying-glass text-slate-400"></i>
                </div>
                <input type="text" name="cari" value="{{ request('cari') }}"
                       placeholder="Cari berdasarkan Nama, NIP, NIK, NPWP, Asal Institusi, atau Bidang Keahlian..." 
                       class="w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white transition">
            </div>

            <!-- Filter Dropdowns -->
            <div class="flex flex-wrap items-center gap-3 pt-1">
                <div class="flex items-center space-x-2 text-sm font-semibold text-slate-500 mr-2">
                    <i class="fa-solid fa-filter"></i>
                    <span>Filter:</span>
                </div>

                <select name="bagian" onchange="this.form.submit()" class="text-sm bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Semua Bagian</option>
                    @foreach($daftarBagian as $bag)
                        <option value="{{ $bag }}" {{ request('bagian') == $bag ? 'selected' : '' }}>{{ $bag }}</option>
                    @endforeach
                </select>

                <select name="pendidikan" onchange="this.form.submit()" class="text-sm bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Semua Pendidikan</option>
                    @foreach(['S1', 'S2', 'S3', 'Sp-1', 'Sp-2', 'Subspesialis'] as $pend)
                        <option value="{{ $pend }}" {{ request('pendidikan') == $pend ? 'selected' : '' }}>{{ $pend }}</option>
                    @endforeach
                </select>

                <button type="submit" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-xl shadow-sm transition">
                    Terapkan
                </button>

                @if(request()->anyFilled(['cari', 'bagian', 'pendidikan', 'institusi']))
                    <a href="{{ route('publik.eksternal') }}" class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-600 text-sm font-medium rounded-xl transition">
                        Reset Filter
                    </a>
                @endif
            </div>
            </div>
        </form>
    </div>

    <!-- 4 Metric Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
        <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm">
            <p class="text-xs font-bold uppercase tracking-wider text-slate-400">TOTAL EKSTERNAL</p>
            <p class="text-4xl font-extrabold text-blue-600 mt-2">{{ number_format($stats['total_eksternal']) }}</p>
        </div>

        <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm">
            <p class="text-xs font-bold uppercase tracking-wider text-slate-400">SPESIALIS / SUBSPESIALIS</p>
            <p class="text-4xl font-extrabold text-indigo-600 mt-2">{{ number_format($stats['total_spesialis']) }}</p>
        </div>

        <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm">
            <p class="text-xs font-bold uppercase tracking-wider text-slate-400">JENJANG DOKTOR (S3)</p>
            <p class="text-4xl font-extrabold text-emerald-600 mt-2">{{ number_format($stats['total_s3']) }}</p>
        </div>

        <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm">
            <p class="text-xs font-bold uppercase tracking-wider text-slate-400">PEMBARUAN TERAKHIR</p>
            <p class="text-sm font-semibold text-slate-700 mt-4 flex items-center">
                <i class="fa-regular fa-clock text-slate-400 mr-2"></i>
                {{ $stats['pembaruan_terakhir'] }}
            </p>
        </div>
    </div>

    <!-- Tabel Data Eksternal -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-sm">
                <thead>
                    <tr class="bg-slate-50/90 border-b border-slate-200 text-slate-600 font-normal text-xs uppercase tracking-wider">
                        <th class="py-4 px-4 text-center w-14 font-normal">NO</th>
                        <th class="py-4 px-4 font-normal">NAMA LENGKAP</th>
                        <th class="py-4 px-4 font-normal">NIP / NUPTK</th>
                        <th class="py-4 px-4 font-normal">NIK / NPWP</th>
                        <th class="py-4 px-4 font-normal">BAGIAN FAKULTAS</th>
                        <th class="py-4 px-4 font-normal">ASAL INSTITUSI / RUMAH SAKIT</th>
                        <th class="py-4 px-4 font-normal">PANGKAT/GOL</th>
                        <th class="py-4 px-4 text-center font-normal">PENDIDIKAN</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700 font-normal">
                    @forelse($eksternals as $index => $row)
                        <tr class="hover:bg-blue-50/40 transition">
                            <td class="py-4 px-4 text-center text-slate-500 font-normal text-sm">
                                {{ $eksternals->firstItem() + $index }}
                            </td>
                            <td class="py-4 px-4 font-normal text-slate-800 text-[15px]">
                                <a href="javascript:void(0)" 
                                   @click="activeRow = {{ json_encode($row) }}; modalOpen = true"
                                   class="hover:text-blue-600 transition leading-snug font-normal text-slate-800">
                                    {{ $row->nama_lengkap }}
                                </a>
                            </td>
                            <td class="py-4 px-4 text-slate-700 font-normal text-[14px]">
                                <div>{{ $row->nip ?? '-' }}</div>
                                <div class="text-slate-400 text-xs">{{ $row->nuptk ? 'NUPTK: '.$row->nuptk : '' }}</div>
                            </td>
                            <td class="py-4 px-4 text-slate-700 font-normal text-[14px]">
                                <div>NIK: {{ $row->nik ?? '-' }}</div>
                                <div class="text-slate-400 text-xs">{{ $row->npwp ? 'NPWP: '.$row->npwp : '' }}</div>
                            </td>
                            <td class="py-4 px-4">
                                @if($row->bagian)
                                    <span class="inline-block px-3 py-1 rounded-lg text-xs font-normal 
                                        {{ $row->bagian === 'Bagian Kedokteran' ? 'bg-blue-50 text-blue-700 border border-blue-200' : '' }}
                                        {{ $row->bagian === 'Bagian Farmasi' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : '' }}
                                        {{ $row->bagian === 'Bagian Keperawatan' ? 'bg-purple-50 text-purple-700 border border-purple-200' : '' }}">
                                        {{ $row->bagian }}
                                    </span>
                                @else
                                    <span class="text-slate-400 text-sm font-normal">-</span>
                                @endif
                            </td>
                            <td class="py-4 px-4 text-slate-700 font-normal text-[14px]">
                                {{ $row->asal_institusi ?? '-' }}
                            </td>
                            <td class="py-4 px-4">
                                @if($row->pangkat_gol)
                                    <span class="inline-block px-3 py-1 rounded-lg text-xs font-normal bg-blue-50 text-blue-700 border border-blue-200">
                                        {{ $row->pangkat_gol }}
                                    </span>
                                @else
                                    <span class="text-slate-400 text-sm font-normal">-</span>
                                @endif
                            </td>
                            <td class="py-4 px-4 text-center">
                                <span class="inline-block px-2.5 py-1 rounded-lg text-xs font-normal bg-slate-100 text-slate-700 border border-slate-200">
                                    {{ $row->pendidikan_terakhir }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-12 text-center text-slate-400">
                                <i class="fa-solid fa-folder-open text-3xl mb-3 text-slate-300"></i>
                                <p class="text-sm">Tidak ditemukan data eksternal yang cocok dengan kriteria pencarian.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($eksternals->hasPages())
            <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/50">
                {{ $eksternals->links() }}
            </div>
        @endif
    </div>

    <!-- Modal Detail Eksternal -->
    <div x-show="modalOpen" x-cloak class="fixed inset-0 z-50 overflow-y-auto" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="modalOpen" @click="modalOpen = false" class="fixed inset-0 bg-slate-900/60 transition-opacity"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>

            <div x-show="modalOpen" 
                 class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-xl w-full">
                
                <div class="bg-gradient-to-r from-blue-700 to-indigo-800 px-6 py-5 text-white">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 rounded-xl bg-white/10 backdrop-blur flex items-center justify-center font-bold">
                                <i class="fa-solid fa-user-group text-lg"></i>
                            </div>
                            <div>
                                <h3 class="text-base font-bold leading-tight" x-text="activeRow.nama_lengkap"></h3>
                                <p class="text-xs text-blue-100" x-text="activeRow.asal_institusi || 'Institusi Eksternal'"></p>
                            </div>
                        </div>
                        <button @click="modalOpen = false" class="text-white/80 hover:text-white text-xl font-bold">&times;</button>
                    </div>
                </div>

                <div class="p-6 space-y-4 text-xs">
                    <div class="grid grid-cols-2 gap-4">
                        <div class="bg-slate-50 p-3 rounded-xl border border-slate-100">
                            <span class="text-slate-400 font-semibold block text-[10px] uppercase">Bagian Fakultas</span>
                            <span class="text-blue-700 font-bold" x-text="activeRow.bagian || '-'"></span>
                        </div>
                        <div class="bg-slate-50 p-3 rounded-xl border border-slate-100">
                            <span class="text-slate-400 font-semibold block text-[10px] uppercase">Pendidikan Terakhir</span>
                            <span class="text-slate-800 font-bold" x-text="activeRow.pendidikan_terakhir"></span>
                        </div>
                        <div class="bg-slate-50 p-3 rounded-xl border border-slate-100">
                            <span class="text-slate-400 font-semibold block text-[10px] uppercase">NIP</span>
                            <span class="text-slate-800 font-mono font-bold" x-text="activeRow.nip || '-'"></span>
                        </div>
                        <div class="bg-slate-50 p-3 rounded-xl border border-slate-100">
                            <span class="text-slate-400 font-semibold block text-[10px] uppercase">NUPTK / NIDN</span>
                            <span class="text-slate-800 font-mono font-bold" x-text="activeRow.nuptk || '-'"></span>
                        </div>
                        <div class="bg-slate-50 p-3 rounded-xl border border-slate-100">
                            <span class="text-slate-400 font-semibold block text-[10px] uppercase">NIK (Kependudukan)</span>
                            <span class="text-slate-800 font-mono font-bold" x-text="activeRow.nik || '-'"></span>
                        </div>
                        <div class="bg-slate-50 p-3 rounded-xl border border-slate-100">
                            <span class="text-slate-400 font-semibold block text-[10px] uppercase">NPWP (Pajak)</span>
                            <span class="text-slate-800 font-mono font-bold" x-text="activeRow.npwp || '-'"></span>
                        </div>
                        <div class="bg-slate-50 p-3 rounded-xl border border-slate-100 sm:col-span-2">
                            <span class="text-slate-400 font-semibold block text-[10px] uppercase">Pangkat / Golongan</span>
                            <span class="text-slate-800 font-bold" x-text="activeRow.pangkat_gol || '-'"></span>
                        </div>
                    </div>

                    <div class="bg-slate-50 p-3 rounded-xl border border-slate-100">
                        <span class="text-slate-400 font-semibold block text-[10px] uppercase">Asal Institusi / Rumah Sakit / Instansi Luar</span>
                        <p class="text-slate-800 font-semibold text-sm mt-0.5" x-text="activeRow.asal_institusi || '-'"></p>
                    </div>

                    <div class="bg-slate-50 p-3 rounded-xl border border-slate-100">
                        <span class="text-slate-400 font-semibold block text-[10px] uppercase">Bidang Keahlian / Kepakaran</span>
                        <span class="text-slate-800 font-medium" x-text="activeRow.bidang_keahlian || 'Belum diisi'"></span>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="bg-slate-50 p-3 rounded-xl border border-slate-100">
                            <span class="text-slate-400 font-semibold block text-[10px] uppercase">Email</span>
                            <span class="text-slate-800 font-medium" x-text="activeRow.email || '-'"></span>
                        </div>
                        <div class="bg-slate-50 p-3 rounded-xl border border-slate-100">
                            <span class="text-slate-400 font-semibold block text-[10px] uppercase">Kontak / No. HP</span>
                            <span class="text-slate-800 font-medium" x-text="activeRow.no_hp || '-'"></span>
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

