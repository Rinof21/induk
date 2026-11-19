<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\AppSetting;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     * HANYA berisi data konfigurasi wajib sistem (Production Ready).
     * Data dummy/demo TIDAK dimasukkan ke sini.
     */
    public function run(): void
    {
        // 1. Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // 2. Buat Permissions Wajib Sistem
        $permissions = [
            'lihat-dosen',
            'tambah-dosen',
            'edit-dosen',
            'hapus-dosen',
            'lihat-eksternal',
            'tambah-eksternal',
            'edit-eksternal',
            'hapus-eksternal',
            'lihat-penguji',
            'tambah-penguji',
            'edit-penguji',
            'hapus-penguji',
            'lihat-pasien-standar',
            'tambah-pasien-standar',
            'edit-pasien-standar',
            'hapus-pasien-standar',
            'lihat-trash',
            'pulihkan-trash',
            'hapus-permanen-trash',
            'kelola-akses-publik',
            'kelola-user',
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm]);
        }

        // 3. Buat Roles Utama
        $superAdminRole = Role::firstOrCreate(['name' => 'Super Admin']);
        $adminFakultasRole = Role::firstOrCreate(['name' => 'Admin Fakultas']);
        $stafFakultasRole = Role::firstOrCreate(['name' => 'Staf Fakultas']);

        // Super Admin memiliki semua hak akses
        $superAdminRole->syncPermissions(Permission::all());

        // Admin Fakultas dapat mengelola CRUD data operasional
        $adminFakultasRole->syncPermissions([
            'lihat-dosen', 'tambah-dosen', 'edit-dosen', 'hapus-dosen',
            'lihat-eksternal', 'tambah-eksternal', 'edit-eksternal', 'hapus-eksternal',
            'lihat-penguji', 'tambah-penguji', 'edit-penguji', 'hapus-penguji',
            'lihat-pasien-standar', 'tambah-pasien-standar', 'edit-pasien-standar', 'hapus-pasien-standar',
            'lihat-trash', 'pulihkan-trash', 'hapus-permanen-trash',
        ]);

        // Staf Fakultas hanya dapat melihat
        $stafFakultasRole->syncPermissions([
            'lihat-dosen',
            'lihat-eksternal',
            'lihat-penguji',
            'lihat-pasien-standar',
        ]);

        // 4. Buat Akun Administrator Utama (Production Account)
        $admin = User::firstOrCreate(
            ['email' => 'admin@untan.ac.id'],
            [
                'name' => 'Administrator Utama (BKU)',
                'password' => Hash::make('password'),
                'fakultas' => 'Bagian Kepegawaian Universitas',
                'no_hp' => '081234567890',
            ]
        );
        $admin->syncRoles([$superAdminRole]);

        // 5. Inisialisasi Pengaturan Konfigurasi Sistem Awal
        AppSetting::setVal('public_token_enabled', '1', 'Status apakah halaman publik memerlukan token akses (1=aktif, 0=nonaktif)');
        AppSetting::setVal('public_access_token', 'kitasehat', 'Token otorisasi untuk mengakses halaman portal publik');
        AppSetting::setVal('public_token_message', 'Portal Data Terintegrasi Dosen, Penguji, dan Pasien Standar. Silakan masukkan token akses resmi yang telah diberikan oleh pengelola fakultas/universitas.', 'Pesan panduan untuk pengunjung portal publik');
        AppSetting::setVal('instansi_nama', 'Bagian Hukum dan Kepegawaian & Fakultas Terkait', 'Nama Unit Pengelola Data');
        AppSetting::setVal('universitas_nama', 'Universitas Tanjungpura', 'Nama Universitas');

        // CATATAN:
        // Untuk memasukkan data dummy / demo saat proses development atau testing lokal,
        // jalankan command berikut:
        // php artisan db:seed --class=DummyDataSeeder
    }
}
