<?php

namespace App\Services;

use App\Models\Dosen;
use Illuminate\Http\UploadedFile;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DosenExcelService
{
    /**
     * Header kolom standar untuk Export & Import Excel
     */
    public const HEADERS = [
        'A' => ['title' => 'NO', 'width' => 6],
        'B' => ['title' => 'NAMA LENGKAP', 'width' => 35],
        'C' => ['title' => 'NIP', 'width' => 24],
        'D' => ['title' => 'NUPTK / NIDN', 'width' => 22],
        'E' => ['title' => 'NIK', 'width' => 20],
        'F' => ['title' => 'NPWP', 'width' => 22],
        'G' => ['title' => 'BAGIAN', 'width' => 24],
        'H' => ['title' => 'JABATAN FUNGSIONAL', 'width' => 22],
        'I' => ['title' => 'PANGKAT / GOLONGAN', 'width' => 24],
        'J' => ['title' => 'PENDIDIKAN TERAKHIR', 'width' => 20],
        'K' => ['title' => 'STATUS IKATAN KERJA', 'width' => 22],
        'L' => ['title' => 'STATUS KEAKTIFAN / TUGAS', 'width' => 26],
        'M' => ['title' => 'EMAIL', 'width' => 30],
        'N' => ['title' => 'NO HP / WHATSAPP', 'width' => 20],
        'O' => ['title' => 'BIDANG KEAHLIAN', 'width' => 35],
    ];

    /**
     * Ekspor Data Dosen ke file XLSX
     */
    public function export($query = null): StreamedResponse
    {
        $dosens = ($query ?? Dosen::query())->orderBy('nama_lengkap', 'asc')->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Data Dosen');

        // 1. Tulis Header Judul Utama
        $sheet->mergeCells('A1:O1');
        $sheet->setCellValue('A1', 'DATA DOSEN INTERNAL FAKULTAS KEDOKTERAN - UNIVERSITAS TANJUNGPURA');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14)->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('1E3A8A'));
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $sheet->mergeCells('A2:O2');
        $sheet->setCellValue('A2', 'Diekspor pada: ' . date('d F Y, H:i:s') . ' WIB | Total: ' . $dosens->count() . ' Dosen');
        $sheet->getStyle('A2')->getFont()->setItalic(true)->setSize(10)->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('64748B'));
        $sheet->getStyle('A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // 2. Tulis Header Tabel di Row 4
        $rowHeader = 4;
        foreach (self::HEADERS as $col => $info) {
            $sheet->setCellValue("{$col}{$rowHeader}", $info['title']);
            $sheet->getColumnDimension($col)->setWidth($info['width']);
        }

        // Styling Header Kolom
        $headerRange = "A{$rowHeader}:O{$rowHeader}";
        $sheet->getStyle($headerRange)->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
                'size' => 10,
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '1E40AF'], // Biru Navy Elegan
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
                'wrapText' => true,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => 'CBD5E1'],
                ],
            ],
        ]);
        $sheet->getRowDimension($rowHeader)->setRowHeight(28);

        // 3. Tulis Isi Data
        $currRow = 5;
        foreach ($dosens as $index => $d) {
            $sheet->setCellValue("A{$currRow}", $index + 1);
            $sheet->setCellValue("B{$currRow}", $d->nama_lengkap);
            
            // Format angka panjang (NIP, NUPTK, NIK, NPWP) secara eksplisit sebagai String agar tidak berubah jadi scientific notation (6.17E+15)
            $sheet->setCellValueExplicit("C{$currRow}", (string) ($d->nip ?? ''), DataType::TYPE_STRING);
            $sheet->setCellValueExplicit("D{$currRow}", (string) ($d->nuptk ?? ''), DataType::TYPE_STRING);
            $sheet->setCellValueExplicit("E{$currRow}", (string) ($d->nik ?? ''), DataType::TYPE_STRING);
            $sheet->setCellValueExplicit("F{$currRow}", (string) ($d->npwp ?? ''), DataType::TYPE_STRING);

            $sheet->setCellValue("G{$currRow}", $d->bagian);
            $sheet->setCellValue("H{$currRow}", $d->jafung);
            $sheet->setCellValue("I{$currRow}", $d->pangkat_gol ?? '-');
            $sheet->setCellValue("J{$currRow}", $d->pendidikan_terakhir);
            $sheet->setCellValue("K{$currRow}", $d->status_ikatan_kerja);
            $sheet->setCellValue("L{$currRow}", $d->status_tugas);
            $sheet->setCellValue("M{$currRow}", $d->email ?? '-');
            $sheet->setCellValueExplicit("N{$currRow}", (string) ($d->no_hp ?? '-'), DataType::TYPE_STRING);
            $sheet->setCellValue("O{$currRow}", $d->bidang_keahlian ?? '-');

            // Alignment data per baris
            $sheet->getStyle("A{$currRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("C{$currRow}:F{$currRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
            $sheet->getStyle("J{$currRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("K{$currRow}:L{$currRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

            // Zebra striping untuk kenyamanan membaca
            if ($index % 2 === 1) {
                $sheet->getStyle("A{$currRow}:O{$currRow}")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('F8FAFC');
            }

            $currRow++;
        }

        // Border seluruh tabel data
        $lastRow = $currRow - 1;
        if ($lastRow >= 5) {
            $sheet->getStyle("A5:O{$lastRow}")->applyFromArray([
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['rgb' => 'E2E8F0'],
                    ],
                ],
                'alignment' => [
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
            ]);
        }

        $filename = 'Data_Dosen_FK_UNTAN_' . date('Ymd_His') . '.xlsx';

        return new StreamedResponse(function () use ($spreadsheet) {
            $writer = new Xlsx($spreadsheet);
            $writer->save('php://output');
        }, 200, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Cache-Control' => 'max-age=0',
        ]);
    }

    /**
     * Download Template Excel Kosong untuk Impor
     */
    public function downloadTemplate(): StreamedResponse
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Template Impor Dosen');

        // Header petunjuk
        $sheet->mergeCells('A1:O1');
        $sheet->setCellValue('A1', 'FORMAT TEMPLATE IMPOR DATA DOSEN');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(12)->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('1E3A8A'));

        $sheet->mergeCells('A2:O2');
        $sheet->setCellValue('A2', 'Catatan: Kolom bertanda (*) WAJIB diisi. Pilihan Bagian: Bagian Kedokteran, Bagian Farmasi, Bagian Keperawatan.');
        $sheet->getStyle('A2')->getFont()->setItalic(true)->setSize(9)->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('64748B'));

        // Header Kolom di Row 4
        $rowHeader = 4;
        $headersTemplate = [
            'A' => ['title' => 'NO', 'width' => 6],
            'B' => ['title' => 'NAMA LENGKAP (*)', 'width' => 35],
            'C' => ['title' => 'NIP', 'width' => 24],
            'D' => ['title' => 'NUPTK / NIDN', 'width' => 22],
            'E' => ['title' => 'NIK', 'width' => 20],
            'F' => ['title' => 'NPWP', 'width' => 22],
            'G' => ['title' => 'BAGIAN (*)', 'width' => 24],
            'H' => ['title' => 'JABATAN FUNGSIONAL (*)', 'width' => 24],
            'I' => ['title' => 'PANGKAT / GOLONGAN', 'width' => 24],
            'J' => ['title' => 'PENDIDIKAN TERAKHIR (*)', 'width' => 22],
            'K' => ['title' => 'STATUS IKATAN KERJA (*)', 'width' => 22],
            'L' => ['title' => 'STATUS KEAKTIFAN / TUGAS (*)', 'width' => 26],
            'M' => ['title' => 'EMAIL', 'width' => 30],
            'N' => ['title' => 'NO HP / WHATSAPP', 'width' => 20],
            'O' => ['title' => 'BIDANG KEAHLIAN', 'width' => 35],
        ];

        foreach ($headersTemplate as $col => $info) {
            $sheet->setCellValue("{$col}{$rowHeader}", $info['title']);
            $sheet->getColumnDimension($col)->setWidth($info['width']);
        }

        $headerRange = "A{$rowHeader}:O{$rowHeader}";
        $sheet->getStyle($headerRange)->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF'], 'size' => 10],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '059669']], // Hijau Emerald Template
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER, 'wrapText' => true],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'CBD5E1']]],
        ]);
        $sheet->getRowDimension($rowHeader)->setRowHeight(28);

        // Contoh Baris 1
        $sheet->setCellValue('A5', '1');
        $sheet->setCellValue('B5', 'Prof. Dr. dr. Budi Santoso, Sp.A(K)');
        $sheet->setCellValueExplicit('C5', '197501012002121001', DataType::TYPE_STRING);
        $sheet->setCellValueExplicit('D5', '9988776655443322', DataType::TYPE_STRING);
        $sheet->setCellValueExplicit('E5', '6171010101750001', DataType::TYPE_STRING);
        $sheet->setCellValueExplicit('F5', '01.234.567.8-701.000', DataType::TYPE_STRING);
        $sheet->setCellValue('G5', 'Bagian Kedokteran');
        $sheet->setCellValue('H5', 'Guru Besar');
        $sheet->setCellValue('I5', 'Pembina Utama / IV/e');
        $sheet->setCellValue('J5', 'S3');
        $sheet->setCellValue('K5', 'Dosen PNS');
        $sheet->setCellValue('L5', 'Aktif');
        $sheet->setCellValue('M5', 'budi.santoso@untan.ac.id');
        $sheet->setCellValueExplicit('N5', '081234567890', DataType::TYPE_STRING);
        $sheet->setCellValue('O5', 'Ilmu Kesehatan Anak & Pediatri');

        // Contoh Baris 2
        $sheet->setCellValue('A6', '2');
        $sheet->setCellValue('B6', 'Dr. apt. Siti Rahmah, M.Si.');
        $sheet->setCellValueExplicit('C6', '198205122008122002', DataType::TYPE_STRING);
        $sheet->setCellValueExplicit('D6', '4455667788990011', DataType::TYPE_STRING);
        $sheet->setCellValueExplicit('E6', '6171025205820002', DataType::TYPE_STRING);
        $sheet->setCellValueExplicit('F6', '02.345.678.9-701.000', DataType::TYPE_STRING);
        $sheet->setCellValue('G6', 'Bagian Farmasi');
        $sheet->setCellValue('H6', 'Lektor Kepala');
        $sheet->setCellValue('I6', 'Pembina / IV/a');
        $sheet->setCellValue('J6', 'S3');
        $sheet->setCellValue('K6', 'Dosen PNS');
        $sheet->setCellValue('L6', 'Aktif');
        $sheet->setCellValue('M6', 'siti.rahmah@untan.ac.id');
        $sheet->setCellValueExplicit('N6', '081345678901', DataType::TYPE_STRING);
        $sheet->setCellValue('O6', 'Farmakologi Bahan Alam & Fitokimia');

        $sheet->getStyle('A5:O6')->applyFromArray([
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'E2E8F0']]],
            'alignment' => ['vertical' => Alignment::VERTICAL_CENTER],
        ]);

        $filename = 'Template_Import_Dosen_FK_UNTAN.xlsx';

        return new StreamedResponse(function () use ($spreadsheet) {
            $writer = new Xlsx($spreadsheet);
            $writer->save('php://output');
        }, 200, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Cache-Control' => 'max-age=0',
        ]);
    }

    /**
     * Impor file XLSX ke tabel dosens
     */
    public function import(UploadedFile $file): array
    {
        $spreadsheet = IOFactory::load($file->getRealPath());
        $sheet = $spreadsheet->getActiveSheet();
        $rows = $sheet->toArray(null, true, true, true);

        // Cari baris header (biasanya row 4 pada template, atau cari row yang ada kata 'NAMA LENGKAP')
        $headerRowIdx = null;
        foreach ($rows as $rIdx => $row) {
            foreach ($row as $val) {
                if (is_string($val) && str_contains(strtoupper($val), 'NAMA LENGKAP')) {
                    $headerRowIdx = $rIdx;
                    break 2;
                }
            }
        }

        if (!$headerRowIdx) {
            return [
                'success' => false,
                'message' => 'Format file tidak sesuai! Kolom header "NAMA LENGKAP" tidak ditemukan pada file Excel.',
                'imported' => 0,
                'updated' => 0,
                'errors' => [],
            ];
        }

        $imported = 0;
        $updated = 0;
        $errors = [];

        // Loop baris data setelah header
        $startRow = $headerRowIdx + 1;
        $totalRows = count($rows);

        for ($i = $startRow; $i <= $totalRows; $i++) {
            $row = $rows[$i] ?? null;
            if (!$row) continue;

            $namaLengkap = trim((string) ($row['B'] ?? ''));
            if (empty($namaLengkap)) {
                // Lewati baris kosong
                continue;
            }

            $nip = trim((string) ($row['C'] ?? ''));
            $nuptk = trim((string) ($row['D'] ?? ''));
            $nik = trim((string) ($row['E'] ?? ''));
            $npwp = trim((string) ($row['F'] ?? ''));
            $bagian = trim((string) ($row['G'] ?? ''));
            $jafung = trim((string) ($row['H'] ?? ''));
            $pangkatGol = trim((string) ($row['I'] ?? ''));
            $pendidikan = trim((string) ($row['J'] ?? ''));
            $ikatanKerja = trim((string) ($row['K'] ?? ''));
            $statusTugas = trim((string) ($row['L'] ?? ''));
            $email = trim((string) ($row['M'] ?? ''));
            $noHp = trim((string) ($row['N'] ?? ''));
            $keahlian = trim((string) ($row['O'] ?? ''));

            // Normalisasi Bagian jika belum lengkap
            if (strcasecmp($bagian, 'Kedokteran') === 0 || strcasecmp($bagian, 'Bagian Kedokteran') === 0) {
                $bagian = 'Bagian Kedokteran';
            } elseif (strcasecmp($bagian, 'Farmasi') === 0 || strcasecmp($bagian, 'Bagian Farmasi') === 0) {
                $bagian = 'Bagian Farmasi';
            } elseif (strcasecmp($bagian, 'Keperawatan') === 0 || strcasecmp($bagian, 'Bagian Keperawatan') === 0) {
                $bagian = 'Bagian Keperawatan';
            }

            // Fallback default nilai wajib jika kosong
            if (empty($bagian)) $bagian = 'Bagian Kedokteran';
            if (empty($jafung)) $jafung = 'Tenaga Pengajar';
            if (empty($pendidikan)) $pendidikan = 'S2';
            if (empty($ikatanKerja)) $ikatanKerja = 'Dosen PNS';
            if (empty($statusTugas)) $statusTugas = 'Aktif';

            $data = [
                'nama_lengkap' => $namaLengkap,
                'nuptk' => !empty($nuptk) ? $nuptk : null,
                'nik' => !empty($nik) ? $nik : null,
                'npwp' => !empty($npwp) ? $npwp : null,
                'bagian' => $bagian,
                'jafung' => $jafung,
                'pangkat_gol' => !empty($pangkatGol) ? $pangkatGol : null,
                'pendidikan_terakhir' => $pendidikan,
                'status_ikatan_kerja' => $ikatanKerja,
                'status_tugas' => $statusTugas,
                'email' => !empty($email) && filter_var($email, FILTER_VALIDATE_EMAIL) ? $email : null,
                'no_hp' => !empty($noHp) ? $noHp : null,
                'bidang_keahlian' => !empty($keahlian) ? $keahlian : null,
            ];

            try {
                if (!empty($nip)) {
                    $existing = Dosen::withTrashed()->where('nip', $nip)->first();
                    if ($existing) {
                        if ($existing->trashed()) {
                            $existing->restore();
                        }
                        $existing->update($data);
                        $updated++;
                    } else {
                        $data['nip'] = $nip;
                        Dosen::create($data);
                        $imported++;
                    }
                } else {
                    $data['nip'] = null;
                    Dosen::create($data);
                    $imported++;
                }
            } catch (\Exception $e) {
                $errors[] = "Baris {$i} ({$namaLengkap}): " . $e->getMessage();
            }
        }

        return [
            'success' => true,
            'message' => "Proses impor selesai! {$imported} data baru ditambahkan, {$updated} data diperbarui.",
            'imported' => $imported,
            'updated' => $updated,
            'errors' => $errors,
        ];
    }
}

