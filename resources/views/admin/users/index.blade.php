@extends('layouts.admin')

@section('title', 'Manajemen Pengguna & Role')
@section('page_title', 'Manajemen Pengguna & Hak Akses (Spatie)')

@section('content')
<div class="space-y-6" x-data="{ deleteModal: false, deleteUrl: '', deleteName: '' }">

    <!-- Top Action Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h3 class="text-xl font-bold text-slate-900">Pengguna & Peran Akses Sistem</h3>
            <p class="text-xs text-slate-500">Kelola akun staf/operator fakultas dan atur hak akses menggunakan Spatie Permission</p>
        </div>
        <div class="flex items-center space-x-2">
            <a href="{{ route('admin.trash.index', ['tipe' => 'users']) }}" 
               class="inline-flex items-center space-x-2 px-3.5 py-2.5 bg-rose-50 hover:bg-rose-100 text-rose-700 text-xs font-bold rounded-xl border border-rose-200 transition">
                <i class="fa-solid fa-trash-can text-xs"></i>
                <span>Kotak Sampah</span>
            </a>
            <a href="{{ route('admin.users.create') }}" 
               class="inline-flex items-center space-x-2 px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-xl shadow-md shadow-blue-600/30 transition">
                <i class="fa-solid fa-user-plus text-xs"></i>
                <span>Tambah Pengguna Baru</span>
            </a>
        </div>
    </div>

    <!-- Data Table -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-slate-500 font-bold uppercase tracking-wider">
                        <th class="py-3.5 px-4 text-center w-12">NO</th>
                        <th class="py-3.5 px-4">NAMA LENGKAP</th>
                        <th class="py-3.5 px-4">EMAIL</th>
                        <th class="py-3.5 px-4">FAKULTAS / UNIT</th>
                        <th class="py-3.5 px-4">NO. TELEPON</th>
                        <th class="py-3.5 px-4 text-center">ROLE (SPATIE)</th>
                        <th class="py-3.5 px-4 text-center w-28">AKSI</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700 font-medium">
                    @forelse($users as $index => $u)
                        <tr class="hover:bg-slate-50 transition">
                            <td class="py-3.5 px-4 text-center text-slate-400">
                                {{ $users->firstItem() + $index }}
                            </td>
                            <td class="py-3.5 px-4 font-bold text-slate-900">
                                {{ $u->name }}
                                @if($u->id === auth()->id())
                                    <span class="ml-1 text-[10px] text-blue-600 font-normal">(Anda)</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 text-slate-600 font-mono text-[11px]">
                                {{ $u->email }}
                            </td>
                            <td class="py-3.5 px-4 text-slate-800">
                                {{ $u->fakultas ?? '-' }}
                            </td>
                            <td class="py-3.5 px-4 text-slate-600">
                                {{ $u->no_hp ?? '-' }}
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                @foreach($u->roles as $r)
                                    <span class="inline-block px-2.5 py-0.5 rounded-full text-[10px] font-bold {{ $r->name === 'Super Admin' ? 'bg-purple-100 text-purple-800' : ($r->name === 'Admin Fakultas' ? 'bg-blue-100 text-blue-800' : 'bg-slate-100 text-slate-700') }}">
                                        {{ $r->name }}
                                    </span>
                                @endforeach
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                <div class="inline-flex items-center space-x-1">
                                    <a href="{{ route('admin.users.edit', $u) }}" 
                                       title="Edit User"
                                       class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </a>
                                    @if($u->id !== auth()->id())
                                        <button type="button" 
                                                @click="deleteModal = true; deleteUrl = '{{ route('admin.users.destroy', $u) }}'; deleteName = '{{ addslashes($u->name) }}'"
                                                title="Hapus User"
                                                class="p-2 text-rose-600 hover:bg-rose-50 rounded-lg transition">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-12 text-center text-slate-400">
                                Belum ada data pengguna.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($users->hasPages())
            <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/50">
                {{ $users->links() }}
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
                    Apakah Anda yakin ingin memindahkan pengguna <strong x-text="deleteName" class="text-slate-800"></strong> ke Kotak Sampah? Akun tidak akan dapat login selama berada di kotak sampah, tetapi dapat dipulihkan sewaktu-waktu.
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

</div>
@endsection

