@extends('layouts.admin')

@section('title', 'Tambah Pengguna Baru')
@section('page_title', 'Tambah Pengguna Baru (Spatie)')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">

    <div class="flex items-center justify-between">
        <div>
            <h3 class="text-xl font-bold text-slate-900">Form Tambah Pengguna Baru</h3>
            <p class="text-xs text-slate-500">Buat akun untuk staf atau operator fakultas dan tetapkan role Spatie</p>
        </div>
        <a href="{{ route('admin.users.index') }}" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold rounded-xl transition">
            &larr; Kembali
        </a>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 sm:p-8">
        <form action="{{ route('admin.users.store') }}" method="POST" class="space-y-6">
            @csrf

            <div class="space-y-4 text-xs">
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Nama Lengkap Pengguna <span class="text-rose-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                           placeholder="Contoh: Budi Prasetyo, S.Kom."
                           class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:bg-white transition">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Alamat Email <span class="text-rose-500">*</span></label>
                        <input type="email" name="email" value="{{ old('email') }}" required
                               placeholder="nama@untan.ac.id"
                               class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:bg-white transition">
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Kata Sandi Akun <span class="text-rose-500">*</span></label>
                        <input type="password" name="password" required
                               placeholder="Minimal 6 karakter"
                               class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:bg-white transition">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Fakultas / Unit Kerja</label>
                        <input type="text" name="fakultas" value="{{ old('fakultas') }}"
                               placeholder="Contoh: Fakultas Kedokteran"
                               class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:bg-white transition">
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Nomor Telepon / WhatsApp</label>
                        <input type="text" name="no_hp" value="{{ old('no_hp') }}"
                               placeholder="Contoh: 08123456789"
                               class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:bg-white transition">
                    </div>
                </div>

                <!-- Pilihan Role Spatie -->
                <div class="pt-2">
                    <label class="block font-bold text-slate-700 mb-2">Pilih Peran & Hak Akses (Spatie Role) <span class="text-rose-500">*</span></label>
                    <div class="space-y-2.5">
                        @foreach($roles as $role)
                            <label class="flex items-start p-3.5 rounded-xl border border-slate-200 hover:bg-slate-50 cursor-pointer transition">
                                <input type="radio" name="role" value="{{ $role->name }}" {{ old('role', 'Admin Fakultas') == $role->name ? 'checked' : '' }}
                                       class="mt-0.5 text-blue-600 focus:ring-blue-500">
                                <div class="ml-3">
                                    <span class="block font-bold text-slate-900">{{ $role->name }}</span>
                                    <span class="block text-[11px] text-slate-500 mt-0.5">
                                        @if($role->name === 'Super Admin')
                                            Akses penuh ke semua modul, pengaturan token publik, dan manajemen pengguna.
                                        @elseif($role->name === 'Admin Fakultas')
                                            Dapat mengelola (CRUD) data dosen, penguji, dan pasien standar.
                                        @else
                                            Hanya memiliki hak akses melihat data internal.
                                        @endif
                                    </span>
                                </div>
                            </label>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="pt-4 border-t border-slate-100 flex items-center justify-end space-x-3">
                <a href="{{ route('admin.users.index') }}" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold rounded-xl transition">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-xl shadow-md shadow-blue-600/30 transition">
                    Simpan Pengguna
                </button>
            </div>
        </form>
    </div>

</div>
@endsection

