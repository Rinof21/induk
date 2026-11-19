<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Dosen;
use App\Models\Eksternal;
use App\Models\Penguji;
use App\Models\PasienStandar;
use App\Models\AppSetting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class AplikasiDataTest extends TestCase
{
    public function test_portal_publik_minta_token_ketika_proteksi_aktif()
    {
        AppSetting::setVal('public_token_enabled', '1');
        AppSetting::setVal('public_access_token', 'TOKEN-UJI-123');

        $response = $this->get('/publik/dosen');
        $response->assertRedirect(route('publik.token.gate'));
    }

    public function test_portal_publik_bisa_diakses_dengan_parameter_token()
    {
        AppSetting::setVal('public_token_enabled', '1');
        AppSetting::setVal('public_access_token', 'TOKEN-UJI-123');

        $response = $this->get('/publik/dosen?token=TOKEN-UJI-123');
        $response->assertStatus(200);
        $response->assertSee('Data Dosen');
    }

    public function test_portal_publik_bisa_diakses_langsung_ketika_token_dinonaktifkan()
    {
        AppSetting::setVal('public_token_enabled', '0');

        $response = $this->get('/publik/dosen');
        $response->assertStatus(200);
        $response->assertSee('Data Dosen');
    }

    public function test_admin_bisa_login_dan_buka_dashboard()
    {
        $admin = User::where('email', 'admin@untan.ac.id')->first();
        if (!$admin) {
            $this->artisan('db:seed');
            $admin = User::where('email', 'admin@untan.ac.id')->first();
        }

        $response = $this->actingAs($admin)->get('/admin/dashboard');
        $response->assertStatus(200);
        $response->assertSee('Dashboard Ringkasan');
        $response->assertSee('Pengaturan Akses Token Halaman Publik');
    }

    public function test_admin_bisa_ubah_token_dan_status_akses_publik()
    {
        $admin = User::where('email', 'admin@untan.ac.id')->first();

        $response = $this->actingAs($admin)->post('/admin/pengaturan-publik/update', [
            'public_token_enabled' => '1',
            'public_access_token' => 'kitasehat',
            'public_token_message' => 'Pesan pengujian token',
        ]);

        $response->assertRedirect(route('admin.pengaturan-publik.index'));
        $this->assertEquals('kitasehat', AppSetting::getVal('public_access_token'));
        $this->assertEquals('1', AppSetting::getVal('public_token_enabled'));
    }

    public function test_crud_dosen()
    {
        $admin = User::where('email', 'admin@untan.ac.id')->first();
        Dosen::withTrashed()->where('nip', '199501012025011009')->forceDelete();

        // Tambah Dosen
        $response = $this->actingAs($admin)->post('/admin/dosen', [
            'nama_lengkap' => 'Dr. Uji Coba, S.T., M.Eng.',
            'nip' => '199501012025011009',
            'nuptk' => '9988776655443322',
            'pangkat_gol' => 'Penata Muda / III/a',
            'jafung' => 'Asisten Ahli',
            'pendidikan_terakhir' => 'S3',
            'bagian' => 'Bagian Kedokteran',
            'program_studi' => 'Pendidikan Dokter',
            'status_ikatan_kerja' => 'Dosen PNS',
            'status_tugas' => 'Aktif',
            'email' => 'ujicoba@untan.ac.id',
            'no_hp' => '081234567899',
            'bidang_keahlian' => 'Robotika',
        ]);

        $response->assertRedirect(route('admin.dosen.index'));
        $this->assertDatabaseHas('dosens', [
            'nip' => '199501012025011009',
            'bagian' => 'Bagian Kedokteran',
        ]);
    }

    public function test_halaman_publik_eksternal_bisa_diakses_dengan_token()
    {
        AppSetting::setVal('public_token_enabled', '1');
        AppSetting::setVal('public_access_token', 'kitasehat');

        $response = $this->get('/publik/eksternal?token=kitasehat');
        $response->assertStatus(200);
        $response->assertSee('Data Tenaga / Dosen Eksternal');
    }

    public function test_crud_eksternal()
    {
        $admin = User::where('email', 'admin@untan.ac.id')->first();
        Eksternal::withTrashed()->where('nip', '198901012020011005')->forceDelete();

        // Tambah Tenaga Eksternal
        $response = $this->actingAs($admin)->post('/admin/eksternal', [
            'nama_lengkap' => 'dr. External Specialist, Sp.A',
            'nip' => '198901012020011005',
            'nuptk' => '9988112233445566',
            'nik' => '6171010101890001',
            'npwp' => '19.999.888.7-701.000',
            'asal_institusi' => 'RSUD dr. Soedarso',
            'pangkat_gol' => 'Pembina / IV/a',
            'pendidikan_terakhir' => 'Sp-1',
            'bagian' => 'Bagian Kedokteran',
            'email' => 'externalspecialist@rsud.go.id',
            'no_hp' => '081234567800',
            'bidang_keahlian' => 'Pediatri Neonatologi',
        ]);

        $response->assertRedirect(route('admin.eksternal.index'));
        $this->assertDatabaseHas('eksternals', [
            'nip' => '198901012020011005',
            'asal_institusi' => 'RSUD dr. Soedarso',
            'bagian' => 'Bagian Kedokteran',
        ]);

        $eksternal = Eksternal::where('nip', '198901012020011005')->first();
        $this->assertNotNull($eksternal);

        // Filter berdasarkan bagian
        $filterResponse = $this->actingAs($admin)->get('/admin/eksternal?bagian=Bagian+Kedokteran');
        $filterResponse->assertStatus(200);
        $filterResponse->assertSee('dr. External Specialist, Sp.A');

        // Edit
        $updateResponse = $this->actingAs($admin)->put("/admin/eksternal/{$eksternal->id}", [
            'nama_lengkap' => 'dr. External Specialist, Sp.A(K)',
            'nip' => '198901012020011005',
            'pendidikan_terakhir' => 'Subspesialis',
            'bagian' => 'Bagian Kedokteran',
            'asal_institusi' => 'RSUD dr. Soedarso Pontianak',
        ]);

        $updateResponse->assertRedirect(route('admin.eksternal.index'));
        $this->assertDatabaseHas('eksternals', [
            'id' => $eksternal->id,
            'nama_lengkap' => 'dr. External Specialist, Sp.A(K)',
            'pendidikan_terakhir' => 'Subspesialis',
            'bagian' => 'Bagian Kedokteran',
        ]);
    }

    public function test_soft_delete_dan_trash_dosen()
    {
        $admin = User::where('email', 'admin@untan.ac.id')->first();

        // Siapkan data dosen uji
        $dosen = Dosen::withTrashed()->where('nip', '199999992025011001')->first();
        if ($dosen) {
            $dosen->forceDelete();
        }

        $dosen = Dosen::create([
            'nama_lengkap' => 'Dr. Soft Delete Test, M.Si.',
            'nip' => '199999992025011001',
            'bagian' => 'Bagian Kedokteran',
            'jafung' => 'Lektor',
            'pendidikan_terakhir' => 'S3',
            'status_ikatan_kerja' => 'Dosen PNS',
            'status_tugas' => 'Aktif',
        ]);

        // 1. Lakukan Soft Delete
        $deleteResponse = $this->actingAs($admin)->delete("/admin/dosen/{$dosen->id}");
        $deleteResponse->assertRedirect(route('admin.dosen.index'));

        // Cek bahwa data masih ada di DB tapi deleted_at terisi
        $this->assertSoftDeleted('dosens', ['id' => $dosen->id]);

        // Cek bahwa data masuk ke halaman Trash
        $trashResponse = $this->actingAs($admin)->get('/admin/trash?tipe=dosen');
        $trashResponse->assertStatus(200);
        $trashResponse->assertSee('Dr. Soft Delete Test, M.Si.');

        // 2. Lakukan Restore
        $restoreResponse = $this->actingAs($admin)->post("/admin/trash/dosen/restore/{$dosen->id}");
        $restoreResponse->assertRedirect(route('admin.trash.index', ['tipe' => 'dosen']));
        $this->assertNotSoftDeleted('dosens', ['id' => $dosen->id]);

        // 3. Lakukan Soft Delete lagi lalu Force Delete
        $dosen->delete();
        $this->assertSoftDeleted('dosens', ['id' => $dosen->id]);

        $forceDeleteResponse = $this->actingAs($admin)->delete("/admin/trash/dosen/force-delete/{$dosen->id}");
        $forceDeleteResponse->assertRedirect(route('admin.trash.index', ['tipe' => 'dosen']));

        // Cek bahwa data benar-benar musnah dari database
        $this->assertDatabaseMissing('dosens', ['id' => $dosen->id]);
    }

    public function test_admin_bisa_unduh_template_excel_dosen()
    {
        $admin = User::where('email', 'admin@untan.ac.id')->first();

        $response = $this->actingAs($admin)->get('/admin/dosen/template/xlsx');
        $response->assertStatus(200);
        $response->assertHeader('Content-Disposition', 'attachment; filename="Template_Import_Dosen_FK_UNTAN.xlsx"');
    }

    public function test_admin_bisa_ekspor_excel_dosen()
    {
        $admin = User::where('email', 'admin@untan.ac.id')->first();

        $response = $this->actingAs($admin)->get('/admin/dosen/export/xlsx');
        $response->assertStatus(200);
        $this->assertStringContainsString('Data_Dosen_FK_UNTAN_', $response->headers->get('Content-Disposition'));
    }

    public function test_admin_bisa_impor_excel_dosen()
    {
        $admin = User::where('email', 'admin@untan.ac.id')->first();
        Dosen::withTrashed()->where('nip', '198511222010121008')->forceDelete();

        // Buat file spreadsheet dummy untuk impor
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        // Baris Header
        $sheet->setCellValue('B4', 'NAMA LENGKAP');
        $sheet->setCellValue('C4', 'NIP');
        $sheet->setCellValue('D4', 'NUPTK / NIDN');
        $sheet->setCellValue('E4', 'NIK');
        $sheet->setCellValue('F4', 'NPWP');
        $sheet->setCellValue('G4', 'BAGIAN');
        $sheet->setCellValue('H4', 'JABATAN FUNGSIONAL');
        $sheet->setCellValue('I4', 'PANGKAT / GOLONGAN');
        $sheet->setCellValue('J4', 'PENDIDIKAN TERAKHIR');
        $sheet->setCellValue('K4', 'STATUS IKATAN KERJA');
        $sheet->setCellValue('L4', 'STATUS KEAKTIFAN / TUGAS');
        $sheet->setCellValue('M4', 'EMAIL');
        $sheet->setCellValue('N4', 'NO HP / WHATSAPP');
        $sheet->setCellValue('O4', 'BIDANG KEAHLIAN');

        // Baris Data
        $sheet->setCellValue('B5', 'Prof. Dr. Impor Sukses, M.Biomed.');
        $sheet->setCellValueExplicit('C5', '198511222010121008', \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
        $sheet->setCellValueExplicit('D5', '1234567890123456', \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
        $sheet->setCellValueExplicit('E5', '6171018511220008', \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
        $sheet->setCellValueExplicit('F5', '09.876.543.2-701.000', \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
        $sheet->setCellValue('G5', 'Bagian Kedokteran');
        $sheet->setCellValue('H5', 'Guru Besar');
        $sheet->setCellValue('I5', 'Pembina Utama Madya / IV/d');
        $sheet->setCellValue('J5', 'S3');
        $sheet->setCellValue('K5', 'Dosen PNS');
        $sheet->setCellValue('L5', 'Aktif');
        $sheet->setCellValue('M5', 'impor.sukses@untan.ac.id');
        $sheet->setCellValue('N5', '081234567888');
        $sheet->setCellValue('O5', 'Biomedik Molekuler');

        $tempPath = tempnam(sys_get_temp_dir(), 'test_import_') . '.xlsx';
        $writer = new Xlsx($spreadsheet);
        $writer->save($tempPath);

        $uploadedFile = new UploadedFile(
            $tempPath,
            'test_import.xlsx',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            null,
            true
        );

        $response = $this->actingAs($admin)->post('/admin/dosen/import/xlsx', [
            'file' => $uploadedFile,
        ]);

        $response->assertRedirect(route('admin.dosen.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('dosens', [
            'nip' => '198511222010121008',
            'nama_lengkap' => 'Prof. Dr. Impor Sukses, M.Biomed.',
            'bagian' => 'Bagian Kedokteran',
            'nik' => '6171018511220008',
        ]);

        if (file_exists($tempPath)) {
            @unlink($tempPath);
        }
    }

    public function test_admin_bisa_unduh_template_excel_eksternal()
    {
        $admin = User::where('email', 'admin@untan.ac.id')->first();

        $response = $this->actingAs($admin)->get('/admin/eksternal/template/xlsx');
        $response->assertStatus(200);
        $response->assertHeader('Content-Disposition', 'attachment; filename="Template_Import_Eksternal_FK_UNTAN.xlsx"');
    }

    public function test_admin_bisa_ekspor_excel_eksternal()
    {
        $admin = User::where('email', 'admin@untan.ac.id')->first();

        $response = $this->actingAs($admin)->get('/admin/eksternal/export/xlsx');
        $response->assertStatus(200);
        $this->assertStringContainsString('Data_Eksternal_FK_UNTAN_', $response->headers->get('Content-Disposition'));
    }

    public function test_admin_bisa_impor_excel_eksternal()
    {
        $admin = User::where('email', 'admin@untan.ac.id')->first();
        Eksternal::withTrashed()->where('nip', '198703152011011009')->forceDelete();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        // Baris Header
        $sheet->setCellValue('B4', 'NAMA LENGKAP');
        $sheet->setCellValue('C4', 'NIP');
        $sheet->setCellValue('D4', 'NUPTK / NIDN');
        $sheet->setCellValue('E4', 'NIK');
        $sheet->setCellValue('F4', 'NPWP');
        $sheet->setCellValue('G4', 'BAGIAN');
        $sheet->setCellValue('H4', 'ASAL INSTITUSI / RUMAH SAKIT');
        $sheet->setCellValue('I4', 'PANGKAT / GOLONGAN');
        $sheet->setCellValue('J4', 'PENDIDIKAN TERAKHIR');
        $sheet->setCellValue('K4', 'EMAIL');
        $sheet->setCellValue('L4', 'NO HP / WHATSAPP');
        $sheet->setCellValue('M4', 'BIDANG KEAHLIAN');

        // Baris Data
        $sheet->setCellValue('B5', 'dr. Eksternal Impor Test, Sp.A');
        $sheet->setCellValueExplicit('C5', '198703152011011009', \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
        $sheet->setCellValueExplicit('D5', '9988112233445566', \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
        $sheet->setCellValueExplicit('E5', '6171011503870009', \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
        $sheet->setCellValueExplicit('F5', '19.876.543.2-701.000', \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
        $sheet->setCellValue('G5', 'Bagian Kedokteran');
        $sheet->setCellValue('H5', 'RSUD Sultan Syarif Mohamad Alkadrie');
        $sheet->setCellValue('I5', 'Penata / III/c');
        $sheet->setCellValue('J5', 'Sp-1');
        $sheet->setCellValue('K5', 'eksternal.impor@rsudssma.id');
        $sheet->setCellValueExplicit('L5', '081234567999', \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
        $sheet->setCellValue('M5', 'Pediatri & Tumbuh Kembang Anak');

        $tempPath = tempnam(sys_get_temp_dir(), 'test_import_ekst_') . '.xlsx';
        $writer = new Xlsx($spreadsheet);
        $writer->save($tempPath);

        $uploadedFile = new UploadedFile(
            $tempPath,
            'test_import_eksternal.xlsx',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            null,
            true
        );

        $response = $this->actingAs($admin)->post('/admin/eksternal/import/xlsx', [
            'file' => $uploadedFile,
        ]);

        $response->assertRedirect(route('admin.eksternal.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('eksternals', [
            'nip' => '198703152011011009',
            'nama_lengkap' => 'dr. Eksternal Impor Test, Sp.A',
            'bagian' => 'Bagian Kedokteran',
            'asal_institusi' => 'RSUD Sultan Syarif Mohamad Alkadrie',
            'nik' => '6171011503870009',
        ]);

        if (file_exists($tempPath)) {
            @unlink($tempPath);
        }
    }
}

