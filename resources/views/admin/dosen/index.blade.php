@extends('layouts.admin')

@section('title', 'Kelola Data Dosen')
@section('page_title', 'Kelola Data Dosen')

@section('content')
<div class="space-y-6" x-data="{ 
    deleteModal: false, 
    deleteUrl: '', 
    deleteName: '', 
    importModal: false, 
    selectedFileName: '',
    selectMode: false,
    selectedIds: [],
    bulkDeleteModal: false,
    selectAll: false,
    toggleSelectAll(ids) {
        if (this.selectAll) {
            this.selectedIds = [...ids];
        } else {
            this.selectedIds = [];
        }
    },
    updateSelectAll(ids) {
        this.selectAll = ids.length > 0 && ids.every(id => this.selectedIds.includes(id));
    }
}">

    <!-- Top Action Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h3 class="text-xl font-bold text-slate-900">Daftar Dosen Internal Fakultas</h3>
            <p class="text-xs text-slate-500">Kelola data tenaga pendidik pada Bagian Kedokteran, Bagian Farmasi, dan Bagian Keperawatan</p>
        </div>
        <div class="flex flex-wrap items-center gap-2">
            <!-- Tombol Mode Checklist Hapus -->
            <button type="button" 
                    @click="selectMode = !selectMode; if (!selectMode) { selectedIds = []; selectAll = false; }"
                    :class="selectMode ? 'bg-amber-100 hover:bg-amber-200 text-amber-900 border-amber-300 ring-2 ring-amber-300/50' : 'bg-white hover:bg-slate-100 text-slate-700 border-slate-300'"
                    title="Tampilkan checkbox untuk memilih data yang akan dihapus massal"
                    class="inline-flex items-center space-x-1.5 px-3 py-2 text-xs font-bold rounded-xl border transition shadow-sm">
                <i class="fa-solid fa-list-check text-xs"></i>
                <span x-text="selectMode ? 'Tutup Checklist' : 'Pilih Data (Checklist)'"></span>
            </button>

            <!-- Tombol Export Excel -->
            <a href="{{ route('admin.dosen.export', request()->query()) }}" 
               title="Ekspor seluruh / hasil filter data dosen ke file Excel (.xlsx)"
               class="inline-flex items-center space-x-1.5 px-3 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl shadow-sm shadow-emerald-600/20 transition">
                <i class="fa-solid fa-file-excel text-xs"></i>
                <span>Export Excel</span>
            </a>

            <!-- Tombol Import Excel -->
            <button type="button" 
                    @click="importModal = true"
                    title="Impor data dosen massal dari file Excel (.xlsx)"
                    class="inline-flex items-center space-x-1.5 px-3 py-2 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 text-xs font-bold rounded-xl border border-indigo-200 transition">
                <i class="fa-solid fa-file-arrow-up text-xs"></i>
                <span>Import Excel</span>
            </button>

            <!-- Kotak Sampah -->
            <a href="{{ route('admin.trash.index', ['tipe' => 'dosen']) }}" 
               class="inline-flex items-center space-x-1.5 px-3 py-2 bg-rose-50 hover:bg-rose-100 text-rose-700 text-xs font-bold rounded-xl border border-rose-200 transition">
                <i class="fa-solid fa-trash-can text-xs"></i>
                <span>Kotak Sampah</span>
            </a>

            <!-- Tambah Dosen Baru -->
            <a href="{{ route('admin.dosen.create') }}" 
               class="inline-flex items-center space-x-1.5 px-3.5 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-xl shadow-md shadow-blue-600/30 transition">
                <i class="fa-solid fa-plus text-xs"></i>
                <span>Tambah Dosen</span>
            </a>
        </div>
    </div>

    <!-- Search & Filter Card -->
    <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm">
        <form method="GET" action="{{ route('admin.dosen.index') }}" class="flex flex-wrap items-center gap-3">
            <div class="relative flex-1 min-w-[240px]">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </div>
                <input type="text" name="cari" value="{{ request('cari') }}" 
                       placeholder="Cari berdasarkan NIP, Nama, Bagian, atau Jafung..."
                       class="w-full pl-10 pr-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-600">
            </div>

            <select name="bagian" onchange="this.form.submit()" class="text-xs bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-slate-700 font-medium">
                <option value="">Semua Bagian</option>
                @foreach($daftarBagian as $b)
                    <option value="{{ $b }}" {{ request('bagian') == $b ? 'selected' : '' }}>{{ $b }}</option>
                @endforeach
            </select>

            <select name="jafung" onchange="this.form.submit()" class="text-xs bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-slate-700">
                <option value="">Semua Jafung</option>
                @foreach(['Tenaga Pengajar', 'Asisten Ahli', 'Lektor', 'Lektor Kepala', 'Guru Besar'] as $jf)
                    <option value="{{ $jf }}" {{ request('jafung') == $jf ? 'selected' : '' }}>{{ $jf }}</option>
                @endforeach
            </select>

            <button type="submit" class="px-4 py-2 bg-slate-800 hover:bg-slate-900 text-white text-xs font-semibold rounded-xl transition">
                Cari
            </button>

            @if(request()->anyFilled(['cari', 'bagian', 'jafung']))
                <a href="{{ route('admin.dosen.index') }}" class="px-3 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 text-xs font-medium rounded-xl transition">
                    Reset
                </a>
            @endif
        </form>
    </div>

    <!-- Bulk Action Bar (Muncul saat ada data terpilih) -->
    <div x-show="selectMode && selectedIds.length > 0" x-cloak 
         class="p-3.5 bg-rose-50 border border-rose-200 rounded-2xl flex flex-wrap items-center justify-between gap-3 shadow-sm transition animate-fade-in">
        <div class="flex items-center space-x-2.5 text-xs text-rose-800">
            <span class="w-6 h-6 rounded-lg bg-rose-100 text-rose-600 flex items-center justify-center font-bold text-xs">
                <i class="fa-solid fa-check"></i>
            </span>
            <span><strong x-text="selectedIds.length"></strong> data dosen dipilih</span>
        </div>
        <div class="flex items-center space-x-2">
            <button type="button" 
                    @click="selectedIds = []; selectAll = false" 
                    class="px-3 py-1.5 bg-white hover:bg-slate-100 text-slate-700 text-xs font-semibold rounded-xl border border-slate-200 transition">
                Batal Pilih
            </button>
            <button type="button" 
                    @click="bulkDeleteModal = true"
                    class="inline-flex items-center space-x-1.5 px-3.5 py-1.5 bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold rounded-xl shadow-md shadow-rose-600/30 transition">
                <i class="fa-solid fa-trash-can text-xs"></i>
                <span>Hapus Terpilih (<span x-text="selectedIds.length"></span>)</span>
            </button>
        </div>
    </div>

    <!-- Data Table -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-slate-500 font-bold uppercase tracking-wider">
                        <th x-show="selectMode" x-cloak class="py-3.5 px-3 text-center w-10">
                            <input type="checkbox" 
                                   @change="toggleSelectAll({{ json_encode($dosens->pluck('id')->toArray()) }})"
                                   x-model="selectAll"
                                   title="Pilih Semua di Halaman Ini"
                                   class="w-4 h-4 text-blue-600 bg-white border-slate-300 rounded focus:ring-blue-500 cursor-pointer">
                        </th>
                        <th class="py-3.5 px-3 text-center w-12">NO</th>
                        <th class="py-3.5 px-4">NAMA LENGKAP</th>
                        <th class="py-3.5 px-4">NIP / NUPTK</th>
                        <th class="py-3.5 px-4">NIK / NPWP</th>
                        <th class="py-3.5 px-4">PANGKAT/GOL</th>
                        <th class="py-3.5 px-4">JAFUNG</th>
                        <th class="py-3.5 px-4">PENDIDIKAN</th>
                        <th class="py-3.5 px-4">BAGIAN</th>
                        <th class="py-3.5 px-4 text-center">STATUS</th>
                        <th class="py-3.5 px-4 text-center w-28">AKSI</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700 font-medium">
                    @forelse($dosens as $index => $dosen)
                        <tr class="hover:bg-slate-50 transition" :class="selectMode && selectedIds.includes({{ $dosen->id }}) ? 'bg-blue-50/50' : ''">
                            <td x-show="selectMode" x-cloak class="py-3.5 px-3 text-center">
                                <input type="checkbox" 
                                       value="{{ $dosen->id }}" 
                                       x-model="selectedIds"
                                       @change="updateSelectAll({{ json_encode($dosens->pluck('id')->toArray()) }})"
                                       class="w-4 h-4 text-blue-600 bg-white border-slate-300 rounded focus:ring-blue-500 cursor-pointer">
                            </td>
                            <td class="py-3.5 px-3 text-center text-slate-400">
                                {{ $dosens->firstItem() + $index }}
                            </td>
                            <td class="py-3.5 px-4 font-bold text-slate-900">
                                {{ $dosen->nama_lengkap }}
                                <p class="text-[11px] text-slate-400 font-normal">{{ $dosen->email ?? '-' }}</p>
                            </td>
                            <td class="py-3.5 px-4 font-mono text-[11px] text-slate-600">
                                <div>{{ $dosen->nip ?? '-' }}</div>
                                <div class="text-slate-400 text-[10px]">{{ $dosen->nuptk ? 'NUPTK: '.$dosen->nuptk : '' }}</div>
                            </td>
                            <td class="py-3.5 px-4 font-mono text-[11px] text-slate-600">
                                <div>NIK: {{ $dosen->nik ?? '-' }}</div>
                                <div class="text-slate-400 text-[10px]">{{ $dosen->npwp ? 'NPWP: '.$dosen->npwp : '' }}</div>
                            </td>
                            <td class="py-3.5 px-4">
                                @if($dosen->pangkat_gol)
                                    <span class="inline-block px-2 py-0.5 rounded text-[11px] font-medium bg-blue-50 text-blue-700 border border-blue-100">
                                        {{ $dosen->pangkat_gol }}
                                    </span>
                                @else
                                    <span class="text-slate-400">-</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 font-semibold text-slate-800">
                                {{ $dosen->jafung }}
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="px-2 py-0.5 rounded text-[11px] font-bold bg-slate-100 text-slate-700">
                                    {{ $dosen->pendidikan_terakhir }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 font-semibold text-blue-900">
                                <span class="px-2.5 py-1 rounded-md text-[11px] font-semibold {{ $dosen->bagian === 'Bagian Kedokteran' ? 'bg-blue-50 text-blue-800 border border-blue-100' : ($dosen->bagian === 'Bagian Farmasi' ? 'bg-emerald-50 text-emerald-800 border border-emerald-100' : 'bg-purple-50 text-purple-800 border border-purple-100') }}">
                                    {{ $dosen->bagian }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                @if($dosen->status_tugas === 'Aktif')
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800">
                                        Aktif
                                    </span>
                                @else
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 text-amber-800">
                                        {{ $dosen->status_tugas }}
                                    </span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                <div class="inline-flex items-center space-x-1">
                                    <a href="{{ route('admin.dosen.edit', $dosen) }}" 
                                       title="Edit Dosen"
                                       class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </a>
                                    <button type="button" 
                                            @click="deleteModal = true; deleteUrl = '{{ route('admin.dosen.destroy', $dosen) }}'; deleteName = '{{ addslashes($dosen->nama_lengkap) }}'"
                                            title="Hapus Dosen"
                                            class="p-2 text-rose-600 hover:bg-rose-50 rounded-lg transition">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td :colspan="selectMode ? 11 : 10" class="py-12 text-center text-slate-400">
                                Belum ada data dosen.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($dosens->hasPages())
            <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/50">
                {{ $dosens->links() }}
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
                    Apakah Anda yakin ingin memindahkan data dosen <strong x-text="deleteName" class="text-slate-800"></strong> ke Kotak Sampah? Data dapat dipulihkan sewaktu-waktu.
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

    <!-- Modal Konfirmasi Hapus Massal -->
    <div x-show="bulkDeleteModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen p-4 text-center">
            <div x-show="bulkDeleteModal" @click="bulkDeleteModal = false" class="fixed inset-0 bg-slate-900/60 transition-opacity"></div>
            
            <div class="bg-white rounded-2xl max-w-sm w-full p-6 text-center shadow-2xl relative z-10">
                <div class="w-12 h-12 rounded-full bg-rose-100 text-rose-600 flex items-center justify-center mx-auto mb-4 text-xl">
                    <i class="fa-solid fa-trash-can"></i>
                </div>
                <h4 class="text-base font-bold text-slate-900">Hapus Data Terpilih?</h4>
                <p class="text-xs text-slate-500 mt-2">
                    Apakah Anda yakin ingin memindahkan <strong x-text="selectedIds.length" class="text-rose-600"></strong> data dosen terpilih ke Kotak Sampah? Data dapat dipulihkan sewaktu-waktu.
                </p>

                <form action="{{ route('admin.dosen.bulk-delete') }}" method="POST" class="mt-6">
                    @csrf
                    <template x-for="id in selectedIds" :key="id">
                        <input type="hidden" name="ids[]" :value="id">
                    </template>
                    <div class="flex items-center space-x-3">
                        <button type="button" @click="bulkDeleteModal = false" class="flex-1 py-2.5 px-4 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold rounded-xl transition">
                            Batal
                        </button>
                        <button type="submit" class="flex-1 py-2.5 px-4 bg-rose-600 hover:bg-rose-700 text-white text-xs font-semibold rounded-xl shadow-md shadow-rose-600/30 transition">
                            Ya, Hapus Terpilih
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Impor Excel -->
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
                            <h4 class="text-base font-bold text-slate-900">Impor Data Dosen (.xlsx)</h4>
                            <p class="text-xs text-slate-500">Unggah data dosen secara massal via spreadsheet Excel</p>
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
                        <span class="text-xs text-blue-900 font-medium">Belum memiliki format file? Unduh template resmi kami.</span>
                    </div>
                    <a href="{{ route('admin.dosen.template') }}" 
                       class="inline-flex items-center space-x-1.5 px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-[11px] font-bold rounded-lg shadow-sm transition shrink-0">
                        <i class="fa-solid fa-download text-[10px]"></i>
                        <span>Unduh Template</span>
                    </a>
                </div>

                <!-- Form Upload Excel -->
                <form action="{{ route('admin.dosen.import') }}" method="POST" enctype="multipart/form-data" class="mt-5 space-y-4">
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
                        <p class="font-bold text-slate-700"><i class="fa-solid fa-lightbulb text-amber-500 mr-1"></i> Aturan Penting Pengisian:</p>
                        <ul class="list-disc list-inside space-y-0.5 text-slate-600 pl-1">
                            <li>NIP/NUPTK/NIK/NPWP tidak akan terpotong / menjadi notasi ilmiah.</li>
                            <li>Jika NIP sudah ada di database, data lama akan <strong>diperbarui (update)</strong> secara otomatis.</li>
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

