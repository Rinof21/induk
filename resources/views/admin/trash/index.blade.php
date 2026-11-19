@extends('layouts.admin')

@section('title', 'Kotak Sampah (Trash)')
@section('page_title', 'Kotak Sampah (Trash)')

@section('content')
<div class="space-y-6" x-data="{ 
    modalRestore: false, 
    modalForceDelete: false, 
    modalRestoreAll: false, 
    modalEmptyTrash: false,
    actionUrl: '', 
    itemName: '' 
}">

    <!-- Top Action Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h3 class="text-xl font-bold text-slate-900 flex items-center space-x-2">
                <i class="fa-solid fa-trash-can text-rose-500"></i>
                <span>Kotak Sampah & Pemulihan Data</span>
            </h3>
            <p class="text-xs text-slate-500">Kelola data yang telah dihapus sementara (Soft Delete). Anda dapat memulihkan atau menghapus permanen.</p>
        </div>

        @if($totalTrash > 0 && $counts[$tipe] > 0)
            <div class="flex items-center space-x-2">
                <!-- Tombol Pulihkan Semua -->
                <button type="button" 
                        @click="modalRestoreAll = true; actionUrl = '{{ route('admin.trash.restore-all', ['tipe' => $tipe]) }}'"
                        class="px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl shadow-sm transition flex items-center space-x-1.5">
                    <i class="fa-solid fa-rotate-left"></i>
                    <span>Pulihkan Semua ({{ $counts[$tipe] }})</span>
                </button>

                <!-- Tombol Kosongkan Sampah -->
                <button type="button" 
                        @click="modalEmptyTrash = true; actionUrl = '{{ route('admin.trash.empty', ['tipe' => $tipe]) }}'"
                        class="px-4 py-2.5 bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold rounded-xl shadow-sm transition flex items-center space-x-1.5">
                    <i class="fa-solid fa-dumpster-fire"></i>
                    <span>Kosongkan Sampah</span>
                </button>
            </div>
        @endif
    </div>

    <!-- Category Tabs -->
    <div class="bg-white p-2 rounded-2xl border border-slate-200 shadow-sm flex flex-wrap gap-2">
        @foreach($labelMap as $key => $label)
            <a href="{{ route('admin.trash.index', ['tipe' => $key]) }}" 
               class="px-4 py-2.5 rounded-xl text-xs font-bold transition flex items-center space-x-2 {{ $tipe === $key ? 'bg-blue-600 text-white shadow-md shadow-blue-600/30' : 'bg-slate-50 hover:bg-slate-100 text-slate-700' }}">
                <span>{{ $label }}</span>
                @if($counts[$key] > 0)
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold {{ $tipe === $key ? 'bg-white text-blue-700' : 'bg-rose-500 text-white' }}">
                        {{ $counts[$key] }}
                    </span>
                @else
                    <span class="px-1.5 py-0.5 rounded-full text-[10px] {{ $tipe === $key ? 'bg-blue-700 text-blue-200' : 'bg-slate-200 text-slate-500' }}">
                        0
                    </span>
                @endif
            </a>
        @endforeach
    </div>

    <!-- Search & Filter Card -->
    <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm">
        <form method="GET" action="{{ route('admin.trash.index') }}" class="flex flex-wrap items-center gap-3">
            <input type="hidden" name="tipe" value="{{ $tipe }}">
            
            <div class="relative flex-1 min-w-[240px]">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </div>
                <input type="text" name="cari" value="{{ request('cari') }}" 
                       placeholder="Cari data terhapus di kategori {{ $labelTipe }}..."
                       class="w-full pl-10 pr-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-600">
            </div>

            <button type="submit" class="px-4 py-2 bg-slate-800 hover:bg-slate-900 text-white text-xs font-semibold rounded-xl transition">
                Cari
            </button>

            @if(request()->filled('cari'))
                <a href="{{ route('admin.trash.index', ['tipe' => $tipe]) }}" class="px-3 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 text-xs font-medium rounded-xl transition">
                    Reset
                </a>
            @endif
        </form>
    </div>

    <!-- Data Table -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-slate-500 font-bold uppercase tracking-wider">
                        <th class="py-3.5 px-4 text-center w-12">NO</th>
                        <th class="py-3.5 px-4">NAMA / IDENTITAS UTAMA</th>
                        <th class="py-3.5 px-4">IDENTITAS PENGENAL</th>
                        <th class="py-3.5 px-4">BAGIAN / ASAL / BIDANG</th>
                        <th class="py-3.5 px-4">WAKTU DIHAPUS</th>
                        <th class="py-3.5 px-4 text-center w-36">TINDAKAN</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700 font-medium">
                    @forelse($items as $index => $row)
                        @php
                            $nama = $row->nama_lengkap ?? $row->name ?? '-';
                            $identitas1 = $row->nip ?? $row->nip_nidn ?? $row->kode_ps ?? $row->email ?? '-';
                            $identitas2 = $row->nuptk ?? $row->nik ?? '';
                            $infoTambahan = $row->bagian ?? $row->asal_institusi ?? $row->unit_kerja ?? $row->pekerjaan ?? $row->fakultas ?? '-';
                        @endphp
                        <tr class="hover:bg-slate-50 transition">
                            <td class="py-3.5 px-4 text-center text-slate-400">
                                {{ $items->firstItem() + $index }}
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="font-bold text-slate-900 block">{{ $nama }}</span>
                                <span class="text-[11px] text-slate-400 font-mono">{{ $row->email ?? '' }}</span>
                            </td>
                            <td class="py-3.5 px-4 font-mono text-[11px] text-slate-600">
                                <div>{{ $identitas1 }}</div>
                                @if($identitas2)
                                    <div class="text-[10px] text-slate-400">{{ $identitas2 }}</div>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 font-semibold text-slate-800">
                                <span class="inline-block px-2 py-0.5 rounded text-[11px] bg-slate-100 border border-slate-200 text-slate-700">
                                    {{ $infoTambahan }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-slate-500 text-[11px]">
                                <div class="font-semibold text-rose-600">{{ $row->deleted_at->diffForHumans() }}</div>
                                <div class="text-[10px] text-slate-400">{{ $row->deleted_at->format('d M Y, H:i') }}</div>
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                <div class="inline-flex items-center space-x-1">
                                    <!-- Tombol Restore -->
                                    <button type="button" 
                                            @click="modalRestore = true; actionUrl = '{{ route('admin.trash.restore', ['tipe' => $tipe, 'id' => $row->id]) }}'; itemName = '{{ addslashes($nama) }}'"
                                            title="Pulihkan Data"
                                            class="px-2.5 py-1.5 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 rounded-lg text-xs font-bold transition flex items-center space-x-1 border border-emerald-200">
                                        <i class="fa-solid fa-rotate-left text-[11px]"></i>
                                        <span>Pulihkan</span>
                                    </button>

                                    <!-- Tombol Force Delete -->
                                    <button type="button" 
                                            @click="modalForceDelete = true; actionUrl = '{{ route('admin.trash.force-delete', ['tipe' => $tipe, 'id' => $row->id]) }}'; itemName = '{{ addslashes($nama) }}'"
                                            title="Hapus Permanen"
                                            class="p-1.5 bg-rose-50 hover:bg-rose-100 text-rose-600 rounded-lg text-xs transition border border-rose-200">
                                        <i class="fa-solid fa-trash text-[11px]"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-12 text-center">
                                <div class="w-16 h-16 mx-auto mb-3 rounded-full bg-emerald-50 text-emerald-500 flex items-center justify-center text-2xl border border-emerald-100">
                                    <i class="fa-solid fa-check"></i>
                                </div>
                                <h4 class="text-sm font-bold text-slate-800">Kotak Sampah Bersih</h4>
                                <p class="text-xs text-slate-500 mt-1 max-w-sm mx-auto">
                                    Tidak ada data terhapus dalam kategori <strong>{{ $labelTipe }}</strong>.
                                </p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($items->hasPages())
            <div class="p-4 border-t border-slate-100 bg-slate-50">
                {{ $items->links() }}
            </div>
        @endif
    </div>

    <!-- MODAL 1: Konfirmasi Pulihkan (Restore) -->
    <div x-show="modalRestore" x-cloak class="fixed inset-0 z-50 overflow-y-auto">
        <div class="min-h-screen px-4 flex items-center justify-center">
            <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" @click="modalRestore = false"></div>

            <div class="relative bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl border border-slate-200 z-10 text-center">
                <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center mx-auto mb-4 text-xl">
                    <i class="fa-solid fa-rotate-left"></i>
                </div>
                <h3 class="text-base font-bold text-slate-900 mb-2">Pulihkan Data Ini?</h3>
                <p class="text-xs text-slate-600 mb-6 leading-relaxed">
                    Data <strong class="text-slate-900" x-text="itemName"></strong> akan dikembalikan ke daftar aktif <span class="font-semibold">{{ $labelTipe }}</span> dan dapat dilihat kembali oleh civitas fakultas.
                </p>

                <form :action="actionUrl" method="POST" class="flex items-center justify-center space-x-3">
                    @csrf
                    <button type="button" @click="modalRestore = false" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold rounded-xl transition">
                        Batal
                    </button>
                    <button type="submit" class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl shadow-md shadow-emerald-600/30 transition">
                        Ya, Pulihkan Data
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- MODAL 2: Konfirmasi Hapus Permanen (Force Delete) -->
    <div x-show="modalForceDelete" x-cloak class="fixed inset-0 z-50 overflow-y-auto">
        <div class="min-h-screen px-4 flex items-center justify-center">
            <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" @click="modalForceDelete = false"></div>

            <div class="relative bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl border border-slate-200 z-10 text-center">
                <div class="w-12 h-12 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center mx-auto mb-4 text-xl">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                </div>
                <h3 class="text-base font-bold text-slate-900 mb-2">Hapus Permanen Data?</h3>
                <p class="text-xs text-slate-600 mb-6 leading-relaxed">
                    Data <strong class="text-slate-900" x-text="itemName"></strong> akan <span class="text-rose-600 font-bold">dihapus selamanya</span> dari database. Tindakan ini <strong class="text-rose-600">tidak dapat dibatalkan</strong>!
                </p>

                <form :action="actionUrl" method="POST" class="flex items-center justify-center space-x-3">
                    @csrf
                    @method('DELETE')
                    <button type="button" @click="modalForceDelete = false" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold rounded-xl transition">
                        Batal
                    </button>
                    <button type="submit" class="px-5 py-2.5 bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold rounded-xl shadow-md shadow-rose-600/30 transition">
                        Hapus Selamanya
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- MODAL 3: Konfirmasi Pulihkan Semua -->
    <div x-show="modalRestoreAll" x-cloak class="fixed inset-0 z-50 overflow-y-auto">
        <div class="min-h-screen px-4 flex items-center justify-center">
            <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" @click="modalRestoreAll = false"></div>

            <div class="relative bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl border border-slate-200 z-10 text-center">
                <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center mx-auto mb-4 text-xl">
                    <i class="fa-solid fa-rotate-left"></i>
                </div>
                <h3 class="text-base font-bold text-slate-900 mb-2">Pulihkan Semua Data {{ $labelTipe }}?</h3>
                <p class="text-xs text-slate-600 mb-6 leading-relaxed">
                    Seluruh data di kotak sampah sebanyak <strong>{{ $counts[$tipe] }} data</strong> akan dikembalikan ke daftar aktif.
                </p>

                <form :action="actionUrl" method="POST" class="flex items-center justify-center space-x-3">
                    @csrf
                    <button type="button" @click="modalRestoreAll = false" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold rounded-xl transition">
                        Batal
                    </button>
                    <button type="submit" class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl shadow-md shadow-emerald-600/30 transition">
                        Ya, Pulihkan Semua
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- MODAL 4: Konfirmasi Kosongkan Sampah -->
    <div x-show="modalEmptyTrash" x-cloak class="fixed inset-0 z-50 overflow-y-auto">
        <div class="min-h-screen px-4 flex items-center justify-center">
            <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" @click="modalEmptyTrash = false"></div>

            <div class="relative bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl border border-slate-200 z-10 text-center">
                <div class="w-12 h-12 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center mx-auto mb-4 text-xl">
                    <i class="fa-solid fa-dumpster-fire"></i>
                </div>
                <h3 class="text-base font-bold text-slate-900 mb-2">Kosongkan Kotak Sampah {{ $labelTipe }}?</h3>
                <p class="text-xs text-slate-600 mb-6 leading-relaxed">
                    Semua data di kotak sampah (<strong>{{ $counts[$tipe] }} data</strong>) akan <span class="text-rose-600 font-bold">dihapus permanen</span> dan tidak dapat dipulihkan kembali.
                </p>

                <form :action="actionUrl" method="POST" class="flex items-center justify-center space-x-3">
                    @csrf
                    <button type="button" @click="modalEmptyTrash = false" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold rounded-xl transition">
                        Batal
                    </button>
                    <button type="submit" class="px-5 py-2.5 bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold rounded-xl shadow-md shadow-rose-600/30 transition">
                        Kosongkan Selamanya
                    </button>
                </form>
            </div>
        </div>
    </div>

</div>
@endsection

