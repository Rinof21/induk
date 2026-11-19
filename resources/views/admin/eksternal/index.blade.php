@extends('layouts.admin')

@section('title', 'Kelola Data Eksternal')
@section('page_title', 'Kelola Data Eksternal')

@section('content')
<div class="space-y-6" x-data="{ deleteModal: false, deleteUrl: '', deleteName: '', importModal: false, selectedFileName: '' }">

    <!-- Top Action Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h3 class="text-xl font-bold text-slate-900">Daftar Tenaga / Pengajar Eksternal</h3>
            <p class="text-xs text-slate-500">Kelola data dosen tamu, tenaga pengajar, dan praktisi ahli dari institusi luar</p>
        </div>
        <div class="flex flex-wrap items-center gap-2">
            <!-- Tombol Export Excel -->
            <a href="{{ route('admin.eksternal.export', request()->query()) }}" 
               title="Ekspor seluruh / hasil filter data eksternal ke file Excel (.xlsx)"
               class="inline-flex items-center space-x-1.5 px-3 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl shadow-sm shadow-emerald-600/20 transition">
                <i class="fa-solid fa-file-excel text-xs"></i>
                <span>Export Excel</span>
            </a>

            <!-- Tombol Import Excel -->
            <button type="button" 
                    @click="importModal = true"
                    title="Impor data tenaga/pengajar eksternal massal dari file Excel (.xlsx)"
                    class="inline-flex items-center space-x-1.5 px-3 py-2 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 text-xs font-bold rounded-xl border border-indigo-200 transition">
                <i class="fa-solid fa-file-arrow-up text-xs"></i>
                <span>Import Excel</span>
            </button>

            <!-- Kotak Sampah -->
            <a href="{{ route('admin.trash.index', ['tipe' => 'eksternal']) }}" 
               class="inline-flex items-center space-x-1.5 px-3 py-2 bg-rose-50 hover:bg-rose-100 text-rose-700 text-xs font-bold rounded-xl border border-rose-200 transition">
                <i class="fa-solid fa-trash-can text-xs"></i>
                <span>Kotak Sampah</span>
            </a>

            <!-- Tambah Data Eksternal -->
            <a href="{{ route('admin.eksternal.create') }}" 
               class="inline-flex items-center space-x-1.5 px-3.5 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-xl shadow-md shadow-blue-600/30 transition">
                <i class="fa-solid fa-plus text-xs"></i>
                <span>Tambah Data Eksternal</span>
            </a>
        </div>
    </div>

    <!-- Search & Filter Card -->
    <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm">
        <form method="GET" action="{{ route('admin.eksternal.index') }}" class="flex flex-wrap items-center gap-3">
            <div class="relative flex-1 min-w-[240px]">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </div>
                <input type="text" name="cari" value="{{ request('cari') }}" 
                       placeholder="Cari berdasarkan Nama, NIP, NIK, NPWP, atau Asal Institusi..."
                       class="w-full pl-10 pr-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-600">
            </div>

            <select name="bagian" onchange="this.form.submit()" class="text-xs bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-slate-700">
                <option value="">Semua Bagian</option>
                @foreach($daftarBagian as $bag)
                    <option value="{{ $bag }}" {{ request('bagian') == $bag ? 'selected' : '' }}>{{ $bag }}</option>
                @endforeach
            </select>

            <select name="pendidikan" onchange="this.form.submit()" class="text-xs bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-slate-700">
                <option value="">Semua Pendidikan</option>
                @foreach(['S1', 'S2', 'S3', 'Sp-1', 'Sp-2', 'Subspesialis'] as $pend)
                    <option value="{{ $pend }}" {{ request('pendidikan') == $pend ? 'selected' : '' }}>{{ $pend }}</option>
                @endforeach
            </select>

            <button type="submit" class="px-4 py-2 bg-slate-800 hover:bg-slate-900 text-white text-xs font-semibold rounded-xl transition">
                Cari
            </button>

            @if(request()->anyFilled(['cari', 'bagian', 'pendidikan', 'institusi']))
                <a href="{{ route('admin.eksternal.index') }}" class="px-3 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 text-xs font-medium rounded-xl transition">
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
                        <th class="py-3.5 px-4">NAMA LENGKAP</th>
                        <th class="py-3.5 px-4">NIP / NUPTK</th>
                        <th class="py-3.5 px-4">NIK / NPWP</th>
                        <th class="py-3.5 px-4">BAGIAN FAKULTAS</th>
                        <th class="py-3.5 px-4">ASAL INSTITUSI / RUMAH SAKIT</th>
                        <th class="py-3.5 px-4">PANGKAT/GOL</th>
                        <th class="py-3.5 px-4">PENDIDIKAN</th>
                        <th class="py-3.5 px-4 text-center w-28">AKSI</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700 font-medium">
                    @forelse($eksternals as $index => $row)
                        <tr class="hover:bg-slate-50 transition">
                            <td class="py-3.5 px-4 text-center text-slate-400">
                                {{ $eksternals->firstItem() + $index }}
                            </td>
                            <td class="py-3.5 px-4 font-bold text-slate-900">
                                {{ $row->nama_lengkap }}
                                <p class="text-[11px] text-slate-400 font-normal">{{ $row->email ?? '-' }}</p>
                            </td>
                            <td class="py-3.5 px-4 font-mono text-[11px] text-slate-600">
                                <div>{{ $row->nip ?? '-' }}</div>
                                <div class="text-slate-400 text-[10px]">{{ $row->nuptk ? 'NUPTK: '.$row->nuptk : '' }}</div>
                            </td>
                            <td class="py-3.5 px-4 font-mono text-[11px] text-slate-600">
                                <div>NIK: {{ $row->nik ?? '-' }}</div>
                                <div class="text-slate-400 text-[10px]">{{ $row->npwp ? 'NPWP: '.$row->npwp : '' }}</div>
                            </td>
                            <td class="py-3.5 px-4">
                                @if($row->bagian)
                                    <span class="inline-block px-2.5 py-1 rounded-lg text-[11px] font-bold 
                                        {{ $row->bagian === 'Bagian Kedokteran' ? 'bg-blue-50 text-blue-700 border border-blue-200' : '' }}
                                        {{ $row->bagian === 'Bagian Farmasi' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : '' }}
                                        {{ $row->bagian === 'Bagian Keperawatan' ? 'bg-purple-50 text-purple-700 border border-purple-200' : '' }}">
                                        {{ $row->bagian }}
                                    </span>
                                @else
                                    <span class="text-slate-400">-</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 font-semibold text-slate-800">
                                {{ $row->asal_institusi ?? '-' }}
                            </td>
                            <td class="py-3.5 px-4">
                                @if($row->pangkat_gol)
                                    <span class="inline-block px-2 py-0.5 rounded text-[11px] font-medium bg-blue-50 text-blue-700 border border-blue-100">
                                        {{ $row->pangkat_gol }}
                                    </span>
                                @else
                                    <span class="text-slate-400">-</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="px-2 py-0.5 rounded text-[11px] font-bold bg-slate-100 text-slate-700">
                                    {{ $row->pendidikan_terakhir }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                <div class="inline-flex items-center space-x-1">
                                    <a href="{{ route('admin.eksternal.edit', $row) }}" 
                                       title="Edit Data Eksternal"
                                       class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </a>
                                    <button type="button" 
                                            @click="deleteModal = true; deleteUrl = '{{ route('admin.eksternal.destroy', $row) }}'; deleteName = '{{ addslashes($row->nama_lengkap) }}'"
                                            title="Hapus Data"
                                            class="p-2 text-rose-600 hover:bg-rose-50 rounded-lg transition">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-12 text-center text-slate-400">
                                Belum ada data eksternal. Silakan klik tombol "Tambah Data Eksternal" untuk menambahkan data baru.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($eksternals->hasPages())
            <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/50">
                {{ $eksternals->links() }}
            </div>
        @endif
    </div>

    <!-- Modal Konfirmasi Hapus -->
    <div x-show="deleteModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen p-4 text-center">
            <div x-show="deleteModal" @click="deleteModal = false" class="fixed inset-0 bg-slate-900/60 transition-opacity"></div>
            
            <div class="bg-white rounded-2xl max-w-sm w-full p-6 text-center shadow-2xl relative z-10">
                <div class="w-12 h-12 rounded-full bg-rose-100 text-rose-600 flex items-center justify-center mx-auto mb-4 text-xl">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                </div>
                <h4 class="text-base font-bold text-slate-900">Pindahkan ke Kotak Sampah?</h4>
                <p class="text-xs text-slate-500 mt-2">
                    Apakah Anda yakin ingin memindahkan data eksternal <strong x-text="deleteName" class="text-slate-800"></strong> ke Kotak Sampah? Data dapat dipulihkan sewaktu-waktu.
                </p>

                <div class="flex items-center space-x-3 mt-6">
                    <button type="button" @click="deleteModal = false" class="flex-1 py-2.5 px-4 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold rounded-xl transition">
                        Batal
                    </button>
                    <form :action="deleteUrl" method="POST" class="flex-1">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full py-2.5 px-4 bg-rose-600 hover:bg-rose-700 text-white text-xs font-semibold rounded-xl shadow-md shadow-rose-600/30 transition">
                            Pindahkan ke Sampah
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Impor Excel Eksternal -->
    <div x-show="importModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen p-4 text-center">
            <div x-show="importModal" @click="importModal = false" class="fixed inset-0 bg-slate-900/60 transition-opacity"></div>
            
            <div class="bg-white rounded-2xl max-w-lg w-full p-6 text-left shadow-2xl relative z-10">
                <!-- Modal Header -->
                <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-lg">
                            <i class="fa-solid fa-file-excel"></i>
                        </div>
                        <div>
                            <h4 class="text-base font-bold text-slate-900">Impor Data Eksternal (.xlsx)</h4>
                            <p class="text-xs text-slate-500">Unggah data tenaga/pengajar eksternal massal via spreadsheet Excel</p>
                        </div>
                    </div>
                    <button type="button" @click="importModal = false" class="text-slate-400 hover:text-slate-600 p-1 rounded-lg">
                        <i class="fa-solid fa-xmark text-lg"></i>
                    </button>
                </div>

                <!-- Template Download Banner -->
                <div class="mt-4 p-3.5 bg-blue-50/70 border border-blue-100 rounded-xl flex items-center justify-between gap-3">
                    <div class="flex items-center space-x-2.5">
                        <i class="fa-solid fa-circle-info text-blue-600 text-sm"></i>
                        <span class="text-xs text-blue-900 font-medium">Belum punya format file? Unduh template resmi kami.</span>
                    </div>
                    <a href="{{ route('admin.eksternal.template') }}" 
                       class="inline-flex items-center space-x-1.5 px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-[11px] font-bold rounded-lg shadow-sm transition shrink-0">
                        <i class="fa-solid fa-download text-[10px]"></i>
                        <span>Unduh Template</span>
                    </a>
                </div>

                <!-- Form Upload Excel -->
                <form action="{{ route('admin.eksternal.import') }}" method="POST" enctype="multipart/form-data" class="mt-5 space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-2">Pilih File Spreadsheet Excel (*.xlsx, *.xls)</label>
                        <div class="relative border-2 border-dashed border-slate-300 hover:border-indigo-400 rounded-2xl p-6 text-center cursor-pointer transition bg-slate-50/60 hover:bg-indigo-50/20">
                            <input type="file" name="file" accept=".xlsx,.xls" required 
                                   @change="selectedFileName = $event.target.files[0] ? $event.target.files[0].name : ''"
                                   class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                            <div class="space-y-2 pointer-events-none">
                                <div class="w-12 h-12 mx-auto rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center text-xl">
                                    <i class="fa-solid fa-cloud-arrow-up"></i>
                                </div>
                                <p class="text-xs font-medium text-slate-700" x-text="selectedFileName ? selectedFileName : 'Klik atau seret file Excel ke sini'"></p>
                                <p class="text-[11px] text-slate-400" x-show="!selectedFileName">Format didukung: .xlsx atau .xls (Maks. 10 MB)</p>
                            </div>
                        </div>
                    </div>

                    <!-- Petunjuk Singkat -->
                    <div class="text-[11px] text-slate-500 bg-slate-50 p-3 rounded-xl border border-slate-200 space-y-1">
                        <p class="font-bold text-slate-700"><i class="fa-solid fa-lightbulb text-amber-500 mr-1"></i> Aturan Pengisian Data Eksternal:</p>
                        <ul class="list-disc list-inside space-y-0.5 text-slate-600 pl-1">
                            <li>Nomor NIP/NUPTK/NIK/NPWP tidak akan terpotong atau rusak.</li>
                            <li>Pencocokan data otomatis (NIP / NIK / Nama & Institusi). Jika sudah ada, data lama akan <strong>diperbarui (update)</strong> atau <strong>dipulihkan dari Kotak Sampah</strong>.</li>
                            <li>Pilihan Bagian: <em>Bagian Kedokteran</em>, <em>Bagian Farmasi</em>, atau <em>Bagian Keperawatan</em>.</li>
                        </ul>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex items-center space-x-3 pt-3">
                        <button type="button" @click="importModal = false" class="flex-1 py-2.5 px-4 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold rounded-xl transition">
                            Batal
                        </button>
                        <button type="submit" class="flex-1 py-2.5 px-4 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold rounded-xl shadow-md shadow-indigo-600/30 transition inline-flex items-center justify-center space-x-1.5">
                            <i class="fa-solid fa-file-import text-xs"></i>
                            <span>Mulai Impor</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>
@endsection

