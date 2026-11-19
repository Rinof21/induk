<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Dosen;
use App\Models\Eksternal;
use App\Models\Penguji;
use App\Models\PasienStandar;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class DummyDataSeeder extends Seeder
{
    /**
     * Jalankan seeder khusus data dummy / demo untuk pengembangan lokal.
     * Tidak akan dijalankan pada database production secara otomatis.
     */
    public function run(): void
    {
        $this->command->info('Memulai seeding data dummy/demo...');

        // 1. Akun Demo Staf & Operator
        $adminFakultasRole = Role::where('name', 'Admin Fakultas')->first();
        $stafFakultasRole = Role::where('name', 'Staf Fakultas')->first();

        if ($adminFakultasRole) {
            $adminFK = User::firstOrCreate(
                ['email' => 'fk@untan.ac.id'],
                [
                    'name' => 'Operator Fakultas Kedokteran (Demo)',
                    'password' => Hash::make('password'),
                    'fakultas' => 'Fakultas Kedokteran',
                    'no_hp' => '081398765432',
                ]
            );
            $adminFK->syncRoles([$adminFakultasRole]);
        }

        if ($stafFakultasRole) {
            $staf = User::firstOrCreate(
                ['email' => 'staf@untan.ac.id'],
                [
                    'name' => 'Staf Akademik Fakultas (Demo)',
                    'password' => Hash::make('password'),
                    'fakultas' => 'Fakultas Hukum',
                    'no_hp' => '085211223344',
                ]
            );
            $staf->syncRoles([$stafFakultasRole]);
        }

        // 2. Data Dummy Dosen Internal Fakultas
        $dosenData = [
            [
                'nama_lengkap' => 'Prof. Dr. dr. Muhammad Arif, Sp.A(K), M.Kes.',
                'nip' => '196805141995031002',
                'nuptk' => '2145746648200012',
                'nik' => '6171011405680001',
                'npwp' => '07.123.456.7-701.000',
                'pangkat_gol' => 'Pembina Utama Madya / IV/d',
                'jafung' => 'Guru Besar',
                'pendidikan_terakhir' => 'S3',
                'bagian' => 'Bagian Kedokteran',
                'status_ikatan_kerja' => 'Dosen PNS',
                'status_tugas' => 'Aktif',
                'email' => 'dr.arif@untan.ac.id',
                'no_hp' => '08115566778',
                'bidang_keahlian' => 'Kesehatan Anak & Pediatri Sosial',
            ],
            [
                'nama_lengkap' => 'Dr. dr. Ratna Sari Dewi, M.Biomed., Sp.PK',
                'nip' => '197509122002122001',
                'nuptk' => '5339753654300023',
                'nik' => '6171025209750002',
                'npwp' => '08.234.567.8-701.000',
                'pangkat_gol' => 'Pembina Tingkat I / IV/b',
                'jafung' => 'Lektor Kepala',
                'pendidikan_terakhir' => 'S3',
                'bagian' => 'Bagian Kedokteran',
                'status_ikatan_kerja' => 'Dosen PNS',
                'status_tugas' => 'Aktif',
                'email' => 'ratna.dewi@untan.ac.id',
                'no_hp' => '081256789012',
                'bidang_keahlian' => 'Patologi Klinik & Biomolekuler',
            ],
            [
                'nama_lengkap' => 'dr. Rizki Pratama, M.Sc., Sp.PD',
                'nip' => '198703212015041003',
                'nuptk' => '9045765666130089',
                'nik' => '6171032103870003',
                'npwp' => '09.345.678.9-701.000',
                'pangkat_gol' => 'Penata / III/c',
                'jafung' => 'Lektor',
                'pendidikan_terakhir' => 'Sp-1',
                'bagian' => 'Bagian Kedokteran',
                'status_ikatan_kerja' => 'Dosen PNS',
                'status_tugas' => 'Tugas Belajar',
                'email' => 'rizki.pratama@untan.ac.id',
                'no_hp' => '081345678123',
                'bidang_keahlian' => 'Ilmu Penyakit Dalam & Pulmonologi',
            ],
            [
                'nama_lengkap' => 'drg. Maya Anggraini, MDSc., Sp.KGA',
                'nip' => '198906152019032014',
                'nuptk' => '7152767669230111',
                'nik' => '6171045506890004',
                'npwp' => '10.456.789.0-701.000',
                'pangkat_gol' => 'Penata Muda Tk.I / III/b',
                'jafung' => 'Asisten Ahli',
                'pendidikan_terakhir' => 'Sp-1',
                'bagian' => 'Bagian Kedokteran',
                'status_ikatan_kerja' => 'Dosen PNS',
                'status_tugas' => 'Aktif',
                'email' => 'maya.anggraini@untan.ac.id',
                'no_hp' => '081399887766',
                'bidang_keahlian' => 'Kedokteran Gigi Anak',
            ],
            [
                'nama_lengkap' => 'Dr. Dian Novita, S.Farm., M.Si., Apt.',
                'nip' => '198304252008122002',
                'nuptk' => '4238761663300078',
                'nik' => '6171016504830005',
                'npwp' => '11.567.890.1-701.000',
                'pangkat_gol' => 'Pembina / IV/a',
                'jafung' => 'Lektor Kepala',
                'pendidikan_terakhir' => 'S3',
                'bagian' => 'Bagian Farmasi',
                'status_ikatan_kerja' => 'Dosen PNS',
                'status_tugas' => 'Aktif',
                'email' => 'dian.novita@untan.ac.id',
                'no_hp' => '081255443322',
                'bidang_keahlian' => 'Farmakologi & Toksikologi Terapan',
            ],
            [
                'nama_lengkap' => 'Apt. Alifah Nur Fitriana, S.Farm., M.Farm.',
                'nip' => '199602242024062001',
                'nuptk' => '6556774675230232',
                'nik' => '6171026402960006',
                'npwp' => '12.678.901.2-701.000',
                'pangkat_gol' => 'Penata Muda / III/a',
                'jafung' => 'Asisten Ahli',
                'pendidikan_terakhir' => 'S2',
                'bagian' => 'Bagian Farmasi',
                'status_ikatan_kerja' => 'Dosen PNS',
                'status_tugas' => 'Aktif',
                'email' => 'alifah.nur@untan.ac.id',
                'no_hp' => '081254320003',
                'bidang_keahlian' => 'Teknologi Farmasi & Formulasi Obat',
            ],
            [
                'nama_lengkap' => 'Apt. Andi Tri Utami, S.Farm., M.Clin.Pharm.',
                'nip' => '199301202024062001',
                'nuptk' => '6452771672230302',
                'nik' => '6171036001930007',
                'npwp' => '13.789.012.3-701.000',
                'pangkat_gol' => 'Penata Muda / III/a',
                'jafung' => 'Asisten Ahli',
                'pendidikan_terakhir' => 'S2',
                'bagian' => 'Bagian Farmasi',
                'status_ikatan_kerja' => 'Dosen PNS',
                'status_tugas' => 'Aktif',
                'email' => 'andi.tri@untan.ac.id',
                'no_hp' => '081254320005',
                'bidang_keahlian' => 'Farmasi Klinik & Monitoring Efek Obat',
            ],
            [
                'nama_lengkap' => 'Prof. Dr. Apt. Slamet Raharjo, M.Si.',
                'nip' => '196208101988031003',
                'nuptk' => '1134740643200054',
                'nik' => '6171011008620008',
                'npwp' => '14.890.123.4-701.000',
                'pangkat_gol' => 'Pembina Utama / IV/e',
                'jafung' => 'Guru Besar',
                'pendidikan_terakhir' => 'S3',
                'bagian' => 'Bagian Farmasi',
                'status_ikatan_kerja' => 'Dosen PNS',
                'status_tugas' => 'Aktif',
                'email' => 'slamet.raharjo@untan.ac.id',
                'no_hp' => '08115700889',
                'bidang_keahlian' => 'Kimia Medisinal & Bahan Alam',
            ],
            [
                'nama_lengkap' => 'Ns. Nurbaiti, S.Kep., M.Kep., Sp.Kep.MB',
                'nip' => '199001222019031016',
                'nuptk' => '6454768669130252',
                'nik' => '6171026201900009',
                'npwp' => '15.901.234.5-701.000',
                'pangkat_gol' => 'Penata / III/c',
                'jafung' => 'Lektor',
                'pendidikan_terakhir' => 'Sp-1',
                'bagian' => 'Bagian Keperawatan',
                'status_ikatan_kerja' => 'Dosen PNS',
                'status_tugas' => 'Aktif',
                'email' => 'nurbaiti.ners@untan.ac.id',
                'no_hp' => '081254320001',
                'bidang_keahlian' => 'Keperawatan Medikal Bedah (KMB)',
            ],
            [
                'nama_lengkap' => 'Ns. Alfonsus Hendri, S.Kep., M.Kep.',
                'nip' => '199208182022031010',
                'nuptk' => '4150770671130353',
                'nik' => '6171031808920010',
                'npwp' => '16.012.345.6-701.000',
                'pangkat_gol' => 'Penata Muda Tk.I / III/b',
                'jafung' => 'Asisten Ahli',
                'pendidikan_terakhir' => 'S2',
                'bagian' => 'Bagian Keperawatan',
                'status_ikatan_kerja' => 'Dosen PNS',
                'status_tugas' => 'Aktif',
                'email' => 'alfonsus.hendri@untan.ac.id',
                'no_hp' => '081254320002',
                'bidang_keahlian' => 'Keperawatan Kritis & Gawat Darurat',
            ],
            [
                'nama_lengkap' => 'Ns. Ananda Pradhitya, M.Kep., Sp.Kep.Anak',
                'nip' => '198910112024061001',
                'nuptk' => '3343767668130313',
                'nik' => '6171041110890011',
                'npwp' => '17.123.456.7-701.000',
                'pangkat_gol' => 'Penata Muda / III/a',
                'jafung' => 'Asisten Ahli',
                'pendidikan_terakhir' => 'Sp-1',
                'bagian' => 'Bagian Keperawatan',
                'status_ikatan_kerja' => 'Dosen PNS',
                'status_tugas' => 'Aktif',
                'email' => 'ananda.tenggara@untan.ac.id',
                'no_hp' => '081254320004',
                'bidang_keahlian' => 'Keperawatan Anak & Maternitas',
            ],
            [
                'nama_lengkap' => 'Ns. Ahmad Fadhil, S.Kep., M.Kep.',
                'nip' => '199107192022031008',
                'nuptk' => '8243769670130145',
                'nik' => '6171011907910012',
                'npwp' => '18.234.567.8-701.000',
                'pangkat_gol' => 'Penata Muda Tk.I / III/b',
                'jafung' => 'Asisten Ahli',
                'pendidikan_terakhir' => 'S2',
                'bagian' => 'Bagian Keperawatan',
                'status_ikatan_kerja' => 'Dosen PNS',
                'status_tugas' => 'Tugas Belajar',
                'email' => 'ahmad.fadhil@untan.ac.id',
                'no_hp' => '081352109876',
                'bidang_keahlian' => 'Keperawatan Jiwa & Komunitas',
            ]
        ];

        foreach ($dosenData as $d) {
            Dosen::firstOrCreate(['nip' => $d['nip']], $d);
        }

        // 3. Data Dummy Penguji
        $pengujiData = [
            [
                'nama_lengkap' => 'dr. Hendra Kusuma, Sp.B, FICS',
                'nip_nidn' => '197803152005011004',
                'unit_kerja' => 'RSUD dr. Soedarso / FK UNTAN',
                'spesialisasi' => 'Bedah Umum & Bedah Digestif',
                'jenis_penguji' => 'Penguji OSCE',
                'no_sertifikat' => 'OSCE-NAS-2024-089',
                'status' => 'Aktif',
                'email' => 'dr.hendra.spb@gmail.com',
                'no_hp' => '081257891234',
                'keterangan' => 'Penguji Nasional OSCE Uji Kompetensi Mahasiswa Program Profesi Dokter (UKMPPD).',
            ],
            [
                'nama_lengkap' => 'dr. Siti Fatimah, Sp.OG, Subsp.Obginsos',
                'nip_nidn' => '198109282008122003',
                'unit_kerja' => 'Fakultas Kedokteran UNTAN',
                'spesialisasi' => 'Obstetri dan Ginekologi',
                'jenis_penguji' => 'Penguji OSCE',
                'no_sertifikat' => 'OSCE-REG-2023-142',
                'status' => 'Aktif',
                'email' => 'siti.fatimah.spog@untan.ac.id',
                'no_hp' => '081345678901',
                'keterangan' => 'Koordinator Stasi Pemeriksaan Kehamilan dan Persalinan.',
            ],
            [
                'nama_lengkap' => 'Dr. dr. Eko Prasetyo, Sp.A, M.Kes.',
                'nip_nidn' => '197604102003121002',
                'unit_kerja' => 'Fakultas Kedokteran UNTAN',
                'spesialisasi' => 'Ilmu Kesehatan Anak',
                'jenis_penguji' => 'Penguji Sidang Profesi',
                'no_sertifikat' => 'KDI-EXAM-2022-019',
                'status' => 'Aktif',
                'email' => 'dr.eko.prasetyo@untan.ac.id',
                'no_hp' => '081256334455',
                'keterangan' => 'Penguji Ujian Komprehensif Departemen Anak.',
            ],
            [
                'nama_lengkap' => 'Prof. Dr. Ir. Thamrin Usman, DEA',
                'nip_nidn' => '196111101987031002',
                'unit_kerja' => 'Fakultas MIPA UNTAN',
                'spesialisasi' => 'Kimia Organik & Bioenergi',
                'jenis_penguji' => 'Penguji Skripsi/Tesis',
                'no_sertifikat' => 'EXAM-POSTGRAD-2021-005',
                'status' => 'Aktif',
                'email' => 'thamrin.usman@untan.ac.id',
                'no_hp' => '0811567890',
                'keterangan' => 'Penguji Utama Disertasi dan Tesis Pascasarjana.',
            ],
            [
                'nama_lengkap' => 'Dr. Hermansyah, S.H., M.Hum.',
                'nip_nidn' => '196504121990021001',
                'unit_kerja' => 'Fakultas Hukum UNTAN',
                'spesialisasi' => 'Hukum Tata Negara & Perundang-undangan',
                'jenis_penguji' => 'Penguji Skripsi/Tesis',
                'no_sertifikat' => 'HUK-EXAM-2023-087',
                'status' => 'Aktif',
                'email' => 'hermansyah.law@untan.ac.id',
                'no_hp' => '081258901234',
                'keterangan' => 'Ketua Penguji Ujian Sidang Tertutup Hukum Tata Negara.',
            ],
            [
                'nama_lengkap' => 'dr. Nadia Maharani, Sp.KJ',
                'nip_nidn' => '198511032014042002',
                'unit_kerja' => 'RSJ Sungai Bangkong / Mitra FK',
                'spesialisasi' => 'Kedokteran Jiwa (Psikiatri)',
                'jenis_penguji' => 'Penguji Eksternal',
                'no_sertifikat' => 'OSCE-EXT-2024-033',
                'status' => 'Aktif',
                'email' => 'dr.nadia.psikiatri@gmail.com',
                'no_hp' => '081377889900',
                'keterangan' => 'Penguji Khusus Stasi Anamnesis dan Konseling Kejiwaan OSCE.',
            ]
        ];

        foreach ($pengujiData as $p) {
            Penguji::firstOrCreate(['nama_lengkap' => $p['nama_lengkap']], $p);
        }

        // 4. Data Dummy Pasien Standar
        $psData = [
            [
                'kode_ps' => 'PS-001',
                'nama_lengkap' => 'Bambang Irawan',
                'jenis_kelamin' => 'Laki-laki',
                'tanggal_lahir' => '1978-04-12',
                'usia' => 48,
                'no_hp' => '081256784401',
                'alamat' => 'Jl. Ahmad Yani No. 45, Pontianak Selatan',
                'pekerjaan' => 'Wiraswasta',
                'peran_dikuasai' => 'Penyakit Jantung Koroner (Nyeri Dada Angina tipikal menjalar ke bahu kiri), Hipertensi Derajat II.',
                'tinggi_badan' => 170,
                'berat_badan' => 74,
                'status_ketersediaan' => 'Tersedia',
                'catatan_khusus' => 'Sangat ekspresif saat mensimulasikan keluhan nyeri skala 8, telah lulus pelatihan OSCE tingkat Nasional.',
            ],
            [
                'kode_ps' => 'PS-002',
                'nama_lengkap' => 'Dewi Lestari, S.Pd.',
                'jenis_kelamin' => 'Perempuan',
                'tanggal_lahir' => '1992-08-25',
                'usia' => 34,
                'no_hp' => '081398712302',
                'alamat' => 'Jl. Danau Sentarum Komp. Flamboyan B3, Pontianak Kota',
                'pekerjaan' => 'Guru Honorer',
                'peran_dikuasai' => 'Ibu Hamil Trimester III dengan Preeklamsia Ringan, Konseling Kontrasepsi Pascapersalinan.',
                'tinggi_badan' => 158,
                'berat_badan' => 58,
                'status_ketersediaan' => 'Tersedia',
                'catatan_khusus' => 'Bisa memerankan kecemasan maternal, komunikatif dan kooperatif dalam stasi anamnesis.',
            ],
            [
                'kode_ps' => 'PS-003',
                'nama_lengkap' => 'M. Kevin Pratama',
                'jenis_kelamin' => 'Laki-laki',
                'tanggal_lahir' => '2003-11-14',
                'usia' => 23,
                'no_hp' => '085245678903',
                'alamat' => 'Jl. Reformasi Gg. Petani No. 12, Pontianak Tenggara',
                'pekerjaan' => 'Mahasiswa',
                'peran_dikuasai' => 'Trauma Muskuloskeletal (Cedera Lutut saat olahraga futsal), Apendisitis Akut dengan nyeri perut kanan bawah.',
                'tinggi_badan' => 176,
                'berat_badan' => 67,
                'status_ketersediaan' => 'Sedang Bertugas',
                'catatan_khusus' => 'Dapat mensimulasikan tanda Rovsing dan Blumberg secara konsisten pada pemeriksaan fisik.',
            ],
            [
                'kode_ps' => 'PS-004',
                'nama_lengkap' => 'Hj. Siti Rohana',
                'jenis_kelamin' => 'Perempuan',
                'tanggal_lahir' => '1965-02-18',
                'usia' => 61,
                'no_hp' => '081254112233',
                'alamat' => 'Jl. Tanjung Raya II No. 88, Pontianak Timur',
                'pekerjaan' => 'Ibu Rumah Tangga',
                'peran_dikuasai' => 'Diabetes Melitus Tipe 2 dengan keluhan polidipsi, polifagi, poliuri, dan kram kesemutan ekstremitas.',
                'tinggi_badan' => 153,
                'berat_badan' => 65,
                'status_ketersediaan' => 'Tersedia',
                'catatan_khusus' => 'Memiliki pengalaman > 5 tahun sebagai Pasien Standar OSCE FK UNTAN.',
            ],
            [
                'kode_ps' => 'PS-005',
                'nama_lengkap' => 'Rendra Wibisono',
                'jenis_kelamin' => 'Laki-laki',
                'tanggal_lahir' => '1988-06-30',
                'usia' => 38,
                'no_hp' => '081352998877',
                'alamat' => 'Jl. Prof. M. Yamin No. 24, Pontianak Selatan',
                'pekerjaan' => 'Karyawan Swasta',
                'peran_dikuasai' => 'Gangguan Cemas Menyeluruh (GAD) dan Depresi Sedang, Konseling Berhenti Merokok.',
                'tinggi_badan' => 168,
                'berat_badan' => 62,
                'status_ketersediaan' => 'Tersedia',
                'catatan_khusus' => 'Sertifikat Pelatihan Simulasi Psikiatri FK UNTAN 2024.',
            ],
            [
                'kode_ps' => 'PS-006',
                'nama_lengkap' => 'Nurul Hidayah',
                'jenis_kelamin' => 'Perempuan',
                'tanggal_lahir' => '1997-10-05',
                'usia' => 29,
                'no_hp' => '085348877665',
                'alamat' => 'Jl. Dr. Wahidin Gg. Sepakat No. 5, Pontianak Kota',
                'pekerjaan' => 'Tenaga Administrasi',
                'peran_dikuasai' => 'Gastritis Kronis / GERD dengan sensasi terbakar di dada, Asma Bronkial Eksaserbasi Ringan.',
                'tinggi_badan' => 160,
                'berat_badan' => 52,
                'status_ketersediaan' => 'Tidak Aktif',
                'catatan_khusus' => 'Sedang mengambil cuti penugasan sementara.',
            ]
        ];

        foreach ($psData as $ps) {
            PasienStandar::firstOrCreate(['kode_ps' => $ps['kode_ps']], $ps);
        }

        // 5. Data Dummy Tenaga / Dosen Eksternal
        $eksternalData = [
            [
                'nama_lengkap' => 'dr. Hendra Kusuma, Sp.OT(K) Hip & Knee',
                'nip' => '198104122008121002',
                'nuptk' => '4455889922110034',
                'nik' => '6171021204810005',
                'npwp' => '15.678.901.2-701.000',
                'asal_institusi' => 'RSUD dr. Soedarso Pontianak',
                'pangkat_gol' => 'Pembina / IV/a',
                'pendidikan_terakhir' => 'Subspesialis',
                'bagian' => 'Bagian Kedokteran',
                'email' => 'hendra.kusuma@soedarso.kalbarprov.go.id',
                'no_hp' => '081256789333',
                'bidang_keahlian' => 'Ortopedi & Rekonstruksi Sendi Panggul dan Lutut',
            ],
            [
                'nama_lengkap' => 'dr. Dessy Novitasari, Sp.KJ',
                'nip' => '198503182011012009',
                'nuptk' => '7788990011223344',
                'nik' => '6171015803850003',
                'npwp' => '16.789.012.3-701.000',
                'asal_institusi' => 'RSJD Sungai Bangkong Pontianak',
                'pangkat_gol' => 'Penata Tk. I / III/d',
                'pendidikan_terakhir' => 'Sp-1',
                'bagian' => 'Bagian Kedokteran',
                'email' => 'dessy.novitasari@rsj-sungaibangkong.id',
                'no_hp' => '081344556677',
                'bidang_keahlian' => 'Psikiatri Komunitas & Kesehatan Jiwa Dewasa',
            ],
            [
                'nama_lengkap' => 'apt. Teguh Wibowo, S.Farm., M.Farm-Klin.',
                'nip' => '198909152014021003',
                'nuptk' => '3322114455667788',
                'nik' => '6171031509890001',
                'npwp' => '17.890.123.4-701.000',
                'asal_institusi' => 'Instalasi Farmasi RSUD dr. Soedarso',
                'pangkat_gol' => 'Penata / III/c',
                'pendidikan_terakhir' => 'S2',
                'bagian' => 'Bagian Farmasi',
                'email' => 'teguh.farmklinik@gmail.com',
                'no_hp' => '081299887766',
                'bidang_keahlian' => 'Farmasi Klinik, Interaksi Obat & Pelayanan Farmasi Rawat Inap',
            ],
            [
                'nama_lengkap' => 'Ns. Fitriani, M.Kep., Sp.Kep.MB',
                'nip' => '198611202010122004',
                'nuptk' => '8899001122334455',
                'nik' => '6171026011860002',
                'npwp' => '18.901.234.5-701.000',
                'asal_institusi' => 'RS Universitas Tanjungpura',
                'pangkat_gol' => 'Penata Tk. I / III/d',
                'pendidikan_terakhir' => 'Sp-1',
                'bagian' => 'Bagian Keperawatan',
                'email' => 'fitriani.ners@rsuntan.ac.id',
                'no_hp' => '081355443322',
                'bidang_keahlian' => 'Keperawatan Medikal Bedah & Critical Care Nursing',
            ],
        ];

        foreach ($eksternalData as $ext) {
            Eksternal::updateOrCreate(
                ['nama_lengkap' => $ext['nama_lengkap']],
                $ext
            );
        }

        $this->command->info('Seeding data dummy selesai! (12 Dosen, 4 Eksternal, 6 Penguji, 6 Pasien Standar, 2 Akun Demo)');
    }
}

