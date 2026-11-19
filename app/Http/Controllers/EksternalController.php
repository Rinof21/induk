<?php

namespace App\Http\Controllers;

use App\Models\Eksternal;
use App\Services\EksternalExcelService;
use Illuminate\Http\Request;

class EksternalController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('cari');
        $bagian = $request->query('bagian');
        $pendidikan = $request->query('pendidikan');
        $institusi = $request->query('institusi');

        $query = Eksternal::query()->search($search);

        if ($bagian) {
            $query->where('bagian', $bagian);
        }

        if ($pendidikan) {
            $query->where('pendidikan_terakhir', $pendidikan);
        }

        if ($institusi) {
            $query->where('asal_institusi', $institusi);
        }

        $eksternals = $query->orderBy('nama_lengkap', 'asc')->paginate(15)->withQueryString();
        $daftarInstitusi = Eksternal::select('asal_institusi')->whereNotNull('asal_institusi')->distinct()->pluck('asal_institusi');
        $daftarBagian = Eksternal::DAFTAR_BAGIAN;

        return view('admin.eksternal.index', compact('eksternals', 'daftarInstitusi', 'daftarBagian'));
    }

    public function create()
    {
        $daftarBagian = Eksternal::DAFTAR_BAGIAN;
        return view('admin.eksternal.create', compact('daftarBagian'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_lengkap' => ['required', 'string', 'max:255'],
            'nip' => ['nullable', 'string', 'max:50'],
            'nuptk' => ['nullable', 'string', 'max:50'],
            'nik' => ['nullable', 'string', 'max:30'],
            'npwp' => ['nullable', 'string', 'max:30'],
            'asal_institusi' => ['nullable', 'string', 'max:255'],
            'pangkat_gol' => ['nullable', 'string', 'max:100'],
            'pendidikan_terakhir' => ['required', 'string', 'max:50'],
            'bagian' => ['nullable', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'no_hp' => ['nullable', 'string', 'max:30'],
            'bidang_keahlian' => ['nullable', 'string'],
        ], [
            'nama_lengkap.required' => 'Nama lengkap pengajar/tenaga eksternal wajib diisi.',
            'pendidikan_terakhir.required' => 'Pendidikan terakhir wajib dipilih.',
            'email.email' => 'Format email tidak valid.',
        ]);

        Eksternal::create($validated);

        return redirect()->route('admin.eksternal.index')
            ->with('success', 'Data Eksternal berhasil ditambahkan!');
    }

    public function show(Eksternal $eksternal)
    {
        return view('admin.eksternal.show', compact('eksternal'));
    }

    public function edit(Eksternal $eksternal)
    {
        $daftarBagian = Eksternal::DAFTAR_BAGIAN;
        return view('admin.eksternal.edit', compact('eksternal', 'daftarBagian'));
    }

    public function update(Request $request, Eksternal $eksternal)
    {
        $validated = $request->validate([
            'nama_lengkap' => ['required', 'string', 'max:255'],
            'nip' => ['nullable', 'string', 'max:50'],
            'nuptk' => ['nullable', 'string', 'max:50'],
            'nik' => ['nullable', 'string', 'max:30'],
            'npwp' => ['nullable', 'string', 'max:30'],
            'asal_institusi' => ['nullable', 'string', 'max:255'],
            'pangkat_gol' => ['nullable', 'string', 'max:100'],
            'pendidikan_terakhir' => ['required', 'string', 'max:50'],
            'bagian' => ['nullable', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'no_hp' => ['nullable', 'string', 'max:30'],
            'bidang_keahlian' => ['nullable', 'string'],
        ], [
            'nama_lengkap.required' => 'Nama lengkap pengajar/tenaga eksternal wajib diisi.',
            'pendidikan_terakhir.required' => 'Pendidikan terakhir wajib dipilih.',
            'email.email' => 'Format email tidak valid.',
        ]);

        $eksternal->update($validated);

        return redirect()->route('admin.eksternal.index')
            ->with('success', 'Data Eksternal berhasil diperbarui!');
    }

    public function destroy(Eksternal $eksternal)
    {
        $nama = $eksternal->nama_lengkap;
        $eksternal->delete();

        return redirect()->route('admin.eksternal.index')
            ->with('success', 'Data Eksternal "' . $nama . '" berhasil dipindahkan ke Kotak Sampah (Trash)!');
    }

    /**
     * Ekspor Data Eksternal ke file Excel (.xlsx)
     */
    public function export(Request $request, EksternalExcelService $excelService)
    {
        $search = $request->query('cari');
        $bagian = $request->query('bagian');
        $pendidikan = $request->query('pendidikan');
        $institusi = $request->query('institusi');

        $query = Eksternal::query()->search($search);

        if ($bagian) {
            $query->where('bagian', $bagian);
        }

        if ($pendidikan) {
            $query->where('pendidikan_terakhir', $pendidikan);
        }

        if ($institusi) {
            $query->where('asal_institusi', $institusi);
        }

        return $excelService->export($query);
    }

    /**
     * Unduh Template Excel Kosong untuk Impor Data Eksternal
     */
    public function downloadTemplate(EksternalExcelService $excelService)
    {
        return $excelService->downloadTemplate();
    }

    /**
     * Impor Data Eksternal dari file Excel (.xlsx)
     */
    public function import(Request $request, EksternalExcelService $excelService)
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
            return redirect()->route('admin.eksternal.index')
                ->with('error', $result['message']);
        }

        $message = $result['message'];
        if (!empty($result['errors'])) {
            $message .= ' Catatan: ' . implode(' | ', array_slice($result['errors'], 0, 3));
        }

        return redirect()->route('admin.eksternal.index')
            ->with('success', $message);
    }
}

