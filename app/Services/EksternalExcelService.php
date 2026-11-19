<?php

namespace App\Services;

use App\Models\Eksternal;
use Illuminate\Http\UploadedFile;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use Symfony\Component\HttpFoundation\StreamedResponse;

class EksternalExcelService
{
    /**
     * Header kolom standar untuk Export & Import Excel Eksternal
     */
    public const HEADERS = [
        'A' => ['title' => 'NO', 'width' => 6],
        'B' => ['title' => 'NAMA LENGKAP', 'width' => 35],
        'C' => ['title' => 'NIP', 'width' => 24],
        'D' => ['title' => 'NUPTK / NIDN', 'width' => 22],
        'E' => ['title' => 'NIK', 'width' => 20],
        'F' => ['title' => 'NPWP', 'width' => 22],
        'G' => ['title' => 'BAGIAN', 'width' => 24],
        'H' => ['title' => 'ASAL INSTITUSI / RUMAH SAKIT', 'width' => 36],
        'I' => ['title' => 'PANGKAT / GOLONGAN', 'width' => 24],
        'J' => ['title' => 'PENDIDIKAN TERAKHIR', 'width' => 20],
        'K' => ['title' => 'EMAIL', 'width' => 30],
        'L' => ['title' => 'NO HP / WHATSAPP', 'width' => 20],
        'M' => ['title' => 'BIDANG KEAHLIAN', 'width' => 35],
    ];

    /**
     * Ekspor Data Eksternal ke file XLSX
     */
    public function export($query = null): StreamedResponse
    {
        $eksternals = ($query ?? Eksternal::query())->orderBy('nama_lengkap', 'asc')->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Data Eksternal');

        // 1. Judul Utama
        $sheet->mergeCells('A1:M1');
        $sheet->setCellValue('A1', 'DATA TENAGA / PENGAJAR EKSTERNAL - FAKULTAS KEDOKTERAN UNTAN');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14)->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('1E3A8A'));
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $sheet->mergeCells('A2:M2');
        $sheet->setCellValue('A2', 'Diekspor pada: ' . date('d F Y, H:i:s') . ' WIB | Total: ' . $eksternals->count() . ' Data Pengajar/Tenaga Eksternal');
        $sheet->getStyle('A2')->getFont()->setItalic(true)->setSize(10)->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('64748B'));
        $sheet->getStyle('A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // 2. Header Kolom di Row 4
        $rowHeader = 4;
        foreach (self::HEADERS as $col => $info) {
            $sheet->setCellValue("{$col}{$rowHeader}", $info['title']);
            $sheet->getColumnDimension($col)->setWidth($info['width']);
        }

        // Styling Header Kolom
        $headerRange = "A{$rowHeader}:M{$rowHeader}";
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
        foreach ($eksternals as $index => $row) {
            $sheet->setCellValue("A{$currRow}", $index + 1);
            $sheet->setCellValue("B{$currRow}", $row->nama_lengkap);
            
            // Format angka panjang (NIP, NUPTK, NIK, NPWP) secara eksplisit sebagai String
            $sheet->setCellValueExplicit("C{$currRow}", (string) ($row->nip ?? ''), DataType::TYPE_STRING);
            $sheet->setCellValueExplicit("D{$currRow}", (string) ($row->nuptk ?? ''), DataType::TYPE_STRING);
            $sheet->setCellValueExplicit("E{$currRow}", (string) ($row->nik ?? ''), DataType::TYPE_STRING);
            $sheet->setCellValueExplicit("F{$currRow}", (string) ($row->npwp ?? ''), DataType::TYPE_STRING);

            $sheet->setCellValue("G{$currRow}", $row->bagian ?? '-');
            $sheet->setCellValue("H{$currRow}", $row->asal_institusi ?? '-');
            $sheet->setCellValue("I{$currRow}", $row->pangkat_gol ?? '-');
            $sheet->setCellValue("J{$currRow}", $row->pendidikan_terakhir);
            $sheet->setCellValue("K{$currRow}", $row->email ?? '-');
            $sheet->setCellValueExplicit("L{$currRow}", (string) ($row->no_hp ?? '-'), DataType::TYPE_STRING);
            $sheet->setCellValue("M{$currRow}", $row->bidang_keahlian ?? '-');

            // Alignment data per baris
            $sheet->getStyle("A{$currRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("C{$currRow}:F{$currRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
            $sheet->getStyle("G{$currRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
            $sheet->getStyle("J{$currRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

            // Zebra striping
            if ($index % 2 === 1) {
                $sheet->getStyle("A{$currRow}:M{$currRow}")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('F8FAFC');
            }

            $currRow++;
        }

        // Border seluruh tabel data
        $lastRow = $currRow - 1;
        if ($lastRow >= 5) {
            $sheet->getStyle("A5:M{$lastRow}")->applyFromArray([
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

        $filename = 'Data_Eksternal_FK_UNTAN_' . date('Ymd_His') . '.xlsx';

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
     * Download Template Excel Kosong untuk Impor Data Eksternal
     */
    public function downloadTemplate(): StreamedResponse
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Template Impor Eksternal');

        // Header petunjuk
        $sheet->mergeCells('A1:M1');
        $sheet->setCellValue('A1', 'FORMAT TEMPLATE IMPOR DATA TENAGA / PENGAJAR EKSTERNAL');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(12)->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('1E3A8A'));

        $sheet->mergeCells('A2:M2');
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
            'H' => ['title' => 'ASAL INSTITUSI / RUMAH SAKIT (*)', 'width' => 36],
            'I' => ['title' => 'PANGKAT / GOLONGAN', 'width' => 24],
            'J' => ['title' => 'PENDIDIKAN TERAKHIR (*)', 'width' => 22],
            'K' => ['title' => 'EMAIL', 'width' => 30],
            'L' => ['title' => 'NO HP / WHATSAPP', 'width' => 20],
            'M' => ['title' => 'BIDANG KEAHLIAN', 'width' => 35],
        ];

        foreach ($headersTemplate as $col => $info) {
            $sheet->setCellValue("{$col}{$rowHeader}", $info['title']);
            $sheet->getColumnDimension($col)->setWidth($info['width']);
        }

        $headerRange = "A{$rowHeader}:M{$rowHeader}";
        $sheet->getStyle($headerRange)->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF'], 'size' => 10],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '059669']], // Emerald
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER, 'wrapText' => true],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'CBD5E1']]],
        ]);
        $sheet->getRowDimension($rowHeader)->setRowHeight(28);

        // Contoh Baris 1
        $sheet->setCellValue('A5', '1');
        $sheet->setCellValue('B5', 'dr. Dessy Novitasari, Sp.KJ');
        $sheet->setCellValueExplicit('C5', '198505182011012005', DataType::TYPE_STRING);
        $sheet->setCellValueExplicit('D5', '7788990011223344', DataType::TYPE_STRING);
        $sheet->setCellValueExplicit('E5', '6171015805850005', DataType::TYPE_STRING);
        $sheet->setCellValueExplicit('F5', '16.789.123.4-701.000', DataType::TYPE_STRING);
        $sheet->setCellValue('G5', 'Bagian Kedokteran');
        $sheet->setCellValue('H5', 'RSJD Sungai Bangkong Pontianak');
        $sheet->setCellValue('I5', 'Penata Tk. I / III/d');
        $sheet->setCellValue('J5', 'Sp-1');
        $sheet->setCellValue('K5', 'dessy.novitasari@rsjd-sungaibangkong.id');
        $sheet->setCellValueExplicit('N5', '081255556677', DataType::TYPE_STRING);
        $sheet->setCellValue('M5', 'Psikiatri Klinis & Kedokteran Jiwa');

        // Contoh Baris 2
        $sheet->setCellValue('A6', '2');
        $sheet->setCellValue('B6', 'apt. Teguh Wibowo, S.Farm., M.Farm-Klin.');
        $sheet->setCellValueExplicit('C6', '199209152014021003', DataType::TYPE_STRING);
        $sheet->setCellValueExplicit('D6', '3322114455667788', DataType::TYPE_STRING);
        $sheet->setCellValueExplicit('E6', '6171021509920001', DataType::TYPE_STRING);
        $sheet->setCellValueExplicit('F6', '17.856.321.4-701.000', DataType::TYPE_STRING);
        $sheet->setCellValue('G6', 'Bagian Farmasi');
        $sheet->setCellValue('H6', 'Instalasi Farmasi RSUD dr. Soedarso');
        $sheet->setCellValue('I6', 'Penata / III/c');
        $sheet->setCellValue('J6', 'S2');
        $sheet->setCellValue('K6', 'teguh.farmklinik@gmail.com');
        $sheet->setCellValueExplicit('L6', '081399887711', DataType::TYPE_STRING);
        $sheet->setCellValue('M6', 'Farmasi Rumah Sakit & Farmakoterapi');

        $sheet->getStyle('A5:M6')->applyFromArray([
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'E2E8F0']]],
            'alignment' => ['vertical' => Alignment::VERTICAL_CENTER],
        ]);

        $filename = 'Template_Import_Eksternal_FK_UNTAN.xlsx';

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
     * Impor file XLSX ke tabel eksternals
     */
    public function import(UploadedFile $file): array
    {
        $spreadsheet = IOFactory::load($file->getRealPath());
        $sheet = $spreadsheet->getActiveSheet();
        $rows = $sheet->toArray(null, true, true, true);

        // Cari baris header
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

        $startRow = $headerRowIdx + 1;
        $totalRows = count($rows);

        for ($i = $startRow; $i <= $totalRows; $i++) {
            $row = $rows[$i] ?? null;
            if (!$row) continue;

            $namaLengkap = trim((string) ($row['B'] ?? ''));
            if (empty($namaLengkap)) {
                continue;
            }

            $nip = trim((string) ($row['C'] ?? ''));
            $nuptk = trim((string) ($row['D'] ?? ''));
            $nik = trim((string) ($row['E'] ?? ''));
            $npwp = trim((string) ($row['F'] ?? ''));
            $bagian = trim((string) ($row['G'] ?? ''));
            $asalInstitusi = trim((string) ($row['H'] ?? ''));
            $pangkatGol = trim((string) ($row['I'] ?? ''));
            $pendidikan = trim((string) ($row['J'] ?? ''));
            $email = trim((string) ($row['K'] ?? ''));
            $noHp = trim((string) ($row['L'] ?? ''));
            $keahlian = trim((string) ($row['M'] ?? ''));

            // Normalisasi Bagian jika diperlukan
            if (strcasecmp($bagian, 'Kedokteran') === 0 || strcasecmp($bagian, 'Bagian Kedokteran') === 0) {
                $bagian = 'Bagian Kedokteran';
            } elseif (strcasecmp($bagian, 'Farmasi') === 0 || strcasecmp($bagian, 'Bagian Farmasi') === 0) {
                $bagian = 'Bagian Farmasi';
            } elseif (strcasecmp($bagian, 'Keperawatan') === 0 || strcasecmp($bagian, 'Bagian Keperawatan') === 0) {
                $bagian = 'Bagian Keperawatan';
            }

            // Fallback default nilai wajib jika kosong
            if (empty($bagian)) $bagian = 'Bagian Kedokteran';
            if (empty($pendidikan)) $pendidikan = 'Sp-1';

            $data = [
                'nama_lengkap' => $namaLengkap,
                'nuptk' => !empty($nuptk) ? $nuptk : null,
                'nik' => !empty($nik) ? $nik : null,
                'npwp' => !empty($npwp) ? $npwp : null,
                'bagian' => $bagian,
                'asal_institusi' => !empty($asalInstitusi) ? $asalInstitusi : null,
                'pangkat_gol' => !empty($pangkatGol) ? $pangkatGol : null,
                'pendidikan_terakhir' => $pendidikan,
                'email' => !empty($email) && filter_var($email, FILTER_VALIDATE_EMAIL) ? $email : null,
                'no_hp' => !empty($noHp) ? $noHp : null,
                'bidang_keahlian' => !empty($keahlian) ? $keahlian : null,
            ];

            try {
                $existing = null;
                // Prioritas pencocokan untuk update data: NIP -> NIK -> Nama Lengkap + Asal Institusi
                if (!empty($nip)) {
                    $existing = Eksternal::withTrashed()->where('nip', $nip)->first();
                }
                if (!$existing && !empty($nik)) {
                    $existing = Eksternal::withTrashed()->where('nik', $nik)->first();
                }
                if (!$existing && !empty($asalInstitusi)) {
                    $existing = Eksternal::withTrashed()
                        ->where('nama_lengkap', $namaLengkap)
                        ->where('asal_institusi', $asalInstitusi)
                        ->first();
                }

                if ($existing) {
                    if ($existing->trashed()) {
                        $existing->restore();
                    }
                    if (!empty($nip)) {
                        $data['nip'] = $nip;
                    }
                    $existing->update($data);
                    $updated++;
                } else {
                    $data['nip'] = !empty($nip) ? $nip : null;
                    Eksternal::create($data);
                    $imported++;
                }
            } catch (\Exception $e) {
                $errors[] = "Baris {$i} ({$namaLengkap}): " . $e->getMessage();
            }
        }

        return [
            'success' => true,
            'message' => "Proses impor selesai! {$imported} data eksternal baru ditambahkan, {$updated} data diperbarui.",
            'imported' => $imported,
            'updated' => $updated,
            'errors' => $errors,
        ];
    }
}

