<?php

namespace App\Http\Controllers;

use App\Models\Dosen;
use App\Services\DosenExcelService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class DosenController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('cari');
        $bagian = $request->query('bagian') ?? $request->query('unit_kerja');
        $jafung = $request->query('jafung');
        $statusTugas = $request->query('status_tugas');

        $query = Dosen::query()->search($search);

        if ($bagian) {
            $query->where('bagian', $bagian);
        }

        if ($jafung) {
            $query->where('jafung', $jafung);
        }

        if ($statusTugas) {
            $query->where('status_tugas', $statusTugas);
        }

        $dosens = $query->orderBy('nama_lengkap', 'asc')->paginate(15)->withQueryString();
        $daftarBagian = Dosen::DAFTAR_BAGIAN;

        return view('admin.dosen.index', compact('dosens', 'daftarBagian'));
    }

    public function create()
    {
        $daftarBagian = Dosen::DAFTAR_BAGIAN;
        return view('admin.dosen.create', compact('daftarBagian'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_lengkap' => ['required', 'string', 'max:255'],
            'nip' => ['nullable', 'string', 'max:50', Rule::unique('dosens', 'nip')->whereNull('deleted_at')],
            'nuptk' => ['nullable', 'string', 'max:50'],
            'nik' => ['nullable', 'string', 'max:30'],
            'npwp' => ['nullable', 'string', 'max:30'],
            'pangkat_gol' => ['nullable', 'string', 'max:100'],
            'jafung' => ['required', 'string', 'max:100'],
            'pendidikan_terakhir' => ['required', 'string', 'max:50'],
            'bagian' => ['required', 'string', 'max:255'],
            'status_ikatan_kerja' => ['required', 'string', 'max:50'],
            'status_tugas' => ['required', 'string', 'max:50'],
            'email' => ['nullable', 'email', 'max:255'],
            'no_hp' => ['nullable', 'string', 'max:30'],
            'bidang_keahlian' => ['nullable', 'string'],
        ], [
            'nama_lengkap.required' => 'Nama lengkap dosen wajib diisi.',
            'nip.unique' => 'NIP sudah terdaftar di sistem.',
            'jafung.required' => 'Jabatan fungsional wajib dipilih.',
            'pendidikan_terakhir.required' => 'Pendidikan terakhir wajib dipilih.',
            'bagian.required' => 'Bagian dosen wajib dipilih.',
            'status_ikatan_kerja.required' => 'Status ikatan kerja wajib dipilih.',
            'status_tugas.required' => 'Status tugas belajar/keaktifan wajib dipilih.',
            'email.email' => 'Format email tidak valid.',
        ]);

        Dosen::create($validated);

        return redirect()->route('admin.dosen.index')
            ->with('success', 'Data dosen berhasil ditambahkan!');
    }

    public function show(Dosen $dosen)
    {
        return view('admin.dosen.show', compact('dosen'));
    }

    public function edit(Dosen $dosen)
    {
        $daftarBagian = Dosen::DAFTAR_BAGIAN;
        return view('admin.dosen.edit', compact('dosen', 'daftarBagian'));
    }

    public function update(Request $request, Dosen $dosen)
    {
        $validated = $request->validate([
            'nama_lengkap' => ['required', 'string', 'max:255'],
            'nip' => ['nullable', 'string', 'max:50', Rule::unique('dosens', 'nip')->whereNull('deleted_at')->ignore($dosen->id)],
            'nuptk' => ['nullable', 'string', 'max:50'],
            'nik' => ['nullable', 'string', 'max:30'],
            'npwp' => ['nullable', 'string', 'max:30'],
            'pangkat_gol' => ['nullable', 'string', 'max:100'],
            'jafung' => ['required', 'string', 'max:100'],
            'pendidikan_terakhir' => ['required', 'string', 'max:50'],
            'bagian' => ['required', 'string', 'max:255'],
            'status_ikatan_kerja' => ['required', 'string', 'max:50'],
            'status_tugas' => ['required', 'string', 'max:50'],
            'email' => ['nullable', 'email', 'max:255'],
            'no_hp' => ['nullable', 'string', 'max:30'],
            'bidang_keahlian' => ['nullable', 'string'],
        ], [
            'nama_lengkap.required' => 'Nama lengkap dosen wajib diisi.',
            'nip.unique' => 'NIP sudah digunakan oleh dosen lain.',
            'jafung.required' => 'Jabatan fungsional wajib dipilih.',
            'pendidikan_terakhir.required' => 'Pendidikan terakhir wajib dipilih.',
            'bagian.required' => 'Bagian dosen wajib dipilih.',
            'status_ikatan_kerja.required' => 'Status ikatan kerja wajib dipilih.',
            'status_tugas.required' => 'Status tugas belajar/keaktifan wajib dipilih.',
            'email.email' => 'Format email tidak valid.',
        ]);

        $dosen->update($validated);

        return redirect()->route('admin.dosen.index')
            ->with('success', 'Data dosen berhasil diperbarui!');
    }

    public function destroy(Dosen $dosen)
    {
        $nama = $dosen->nama_lengkap;
        $dosen->delete();

        return redirect()->route('admin.dosen.index')
            ->with('success', 'Data dosen "' . $nama . '" berhasil dipindahkan ke Kotak Sampah (Trash)!');
    }

    /**
     * Ekspor Data Dosen ke file Excel (.xlsx)
     */
    public function export(Request $request, DosenExcelService $excelService)
    {
        $search = $request->query('cari');
        $bagian = $request->query('bagian') ?? $request->query('unit_kerja');
        $jafung = $request->query('jafung');
        $statusTugas = $request->query('status_tugas');

        $query = Dosen::query()->search($search);

        if ($bagian) {
            $query->where('bagian', $bagian);
        }

        if ($jafung) {
            $query->where('jafung', $jafung);
        }

        if ($statusTugas) {
            $query->where('status_tugas', $statusTugas);
        }

        return $excelService->export($query);
    }

    /**
     * Unduh Template Excel Kosong untuk Impor
     */
    public function downloadTemplate(DosenExcelService $excelService)
    {
        return $excelService->downloadTemplate();
    }

    /**
     * Impor Data Dosen dari file Excel (.xlsx)
     */
    public function import(Request $request, DosenExcelService $excelService)
    {
        $request->validate([
            'file' => ['required', 'file', 'mimes:xlsx,xls', 'max:10240'],
        ], [
            'file.required' => 'Silakan pilih file Excel (.xlsx) yang akan diimpor.',
            'file.mimes' => 'Format file harus berupa spreadsheet Excel (.xlsx atau .xls).',
            'file.max' => 'Ukuran file Excel maksimal 10 MB.',
        ]);

        $result = $excelService->import($request->file('file'));

        if (!$result['success']) {
            return redirect()->route('admin.dosen.index')
                ->with('error', $result['message']);
        }

        $message = $result['message'];
        if (!empty($result['errors'])) {
            $message .= ' Catatan: ' . implode(' | ', array_slice($result['errors'], 0, 3));
        }

        return redirect()->route('admin.dosen.index')
            ->with('success', $message);
    }
}
