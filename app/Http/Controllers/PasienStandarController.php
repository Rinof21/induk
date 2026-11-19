<?php

namespace App\Http\Controllers;

use App\Models\PasienStandar;
use Illuminate\Http\Request;

class PasienStandarController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('cari');
        $statusKetersediaan = $request->query('status_ketersediaan');
        $jenisKelamin = $request->query('jenis_kelamin');

        $query = PasienStandar::query()->search($search);

        if ($statusKetersediaan) {
            $query->where('status_ketersediaan', $statusKetersediaan);
        }

        if ($jenisKelamin) {
            $query->where('jenis_kelamin', $jenisKelamin);
        }

        $pasiens = $query->orderBy('kode_ps', 'asc')->paginate(15)->withQueryString();

        return view('admin.pasien_standar.index', compact('pasiens'));
    }

    public function create()
    {
        // Generate kode PS berikutnya secara otomatis
        $lastPs = PasienStandar::orderBy('id', 'desc')->first();
        $nextNumber = $lastPs ? ((int) filter_var($lastPs->kode_ps, FILTER_SANITIZE_NUMBER_INT) + 1) : 1;
        $suggestedCode = 'PS-' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);

        return view('admin.pasien_standar.create', compact('suggestedCode'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode_ps' => ['required', 'string', 'max:50', 'unique:pasien_standars,kode_ps'],
            'nama_lengkap' => ['required', 'string', 'max:255'],
            'jenis_kelamin' => ['required', 'in:Laki-laki,Perempuan'],
            'tanggal_lahir' => ['nullable', 'date'],
            'usia' => ['nullable', 'integer', 'min:1', 'max:120'],
            'no_hp' => ['nullable', 'string', 'max:30'],
            'alamat' => ['nullable', 'string'],
            'pekerjaan' => ['nullable', 'string', 'max:255'],
            'peran_dikuasai' => ['nullable', 'string'],
            'tinggi_badan' => ['nullable', 'integer', 'min:30', 'max:250'],
            'berat_badan' => ['nullable', 'integer', 'min:10', 'max:250'],
            'status_ketersediaan' => ['required', 'string', 'max:50'],
            'catatan_khusus' => ['nullable', 'string'],
        ], [
            'kode_ps.required' => 'Kode Pasien Standar wajib diisi.',
            'kode_ps.unique' => 'Kode Pasien Standar sudah terdaftar.',
            'nama_lengkap.required' => 'Nama lengkap pasien standar wajib diisi.',
            'jenis_kelamin.required' => 'Jenis kelamin wajib dipilih.',
            'status_ketersediaan.required' => 'Status ketersediaan wajib dipilih.',
            'usia.integer' => 'Usia harus berupa angka bilangan bulat.',
        ]);

        PasienStandar::create($validated);

        return redirect()->route('admin.pasien-standar.index')
            ->with('success', 'Data Pasien Standar berhasil ditambahkan!');
    }

    public function show(PasienStandar $pasienStandar)
    {
        return view('admin.pasien_standar.show', compact('pasienStandar'));
    }

    public function edit(PasienStandar $pasienStandar)
    {
        return view('admin.pasien_standar.edit', compact('pasienStandar'));
    }

    public function update(Request $request, PasienStandar $pasienStandar)
    {
        $validated = $request->validate([
            'kode_ps' => ['required', 'string', 'max:50', 'unique:pasien_standars,kode_ps,' . $pasienStandar->id],
            'nama_lengkap' => ['required', 'string', 'max:255'],
            'jenis_kelamin' => ['required', 'in:Laki-laki,Perempuan'],
            'tanggal_lahir' => ['nullable', 'date'],
            'usia' => ['nullable', 'integer', 'min:1', 'max:120'],
            'no_hp' => ['nullable', 'string', 'max:30'],
            'alamat' => ['nullable', 'string'],
            'pekerjaan' => ['nullable', 'string', 'max:255'],
            'peran_dikuasai' => ['nullable', 'string'],
            'tinggi_badan' => ['nullable', 'integer', 'min:30', 'max:250'],
            'berat_badan' => ['nullable', 'integer', 'min:10', 'max:250'],
            'status_ketersediaan' => ['required', 'string', 'max:50'],
            'catatan_khusus' => ['nullable', 'string'],
        ], [
            'kode_ps.required' => 'Kode Pasien Standar wajib diisi.',
            'kode_ps.unique' => 'Kode Pasien Standar sudah digunakan oleh pasien lain.',
            'nama_lengkap.required' => 'Nama lengkap pasien standar wajib diisi.',
            'jenis_kelamin.required' => 'Jenis kelamin wajib dipilih.',
            'status_ketersediaan.required' => 'Status ketersediaan wajib dipilih.',
            'usia.integer' => 'Usia harus berupa angka bilangan bulat.',
        ]);

        $pasienStandar->update($validated);

        return redirect()->route('admin.pasien-standar.index')
            ->with('success', 'Data Pasien Standar berhasil diperbarui!');
    }

    public function destroy(PasienStandar $pasienStandar)
    {
        $nama = $pasienStandar->nama_lengkap;
        $pasienStandar->delete();

        return redirect()->route('admin.pasien-standar.index')
            ->with('success', 'Data Pasien Standar "' . $nama . '" berhasil dipindahkan ke Kotak Sampah (Trash)!');
    }
}

