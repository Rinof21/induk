<?php

namespace App\Http\Controllers;

use App\Models\Penguji;
use Illuminate\Http\Request;

class PengujiController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('cari');
        $jenisPenguji = $request->query('jenis_penguji');
        $status = $request->query('status');

        $query = Penguji::query()->search($search);

        if ($jenisPenguji) {
            $query->where('jenis_penguji', $jenisPenguji);
        }

        if ($status) {
            $query->where('status', $status);
        }

        $pengujis = $query->orderBy('nama_lengkap', 'asc')->paginate(15)->withQueryString();
        $daftarJenisPenguji = Penguji::select('jenis_penguji')->distinct()->pluck('jenis_penguji');

        return view('admin.penguji.index', compact('pengujis', 'daftarJenisPenguji'));
    }

    public function create()
    {
        return view('admin.penguji.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_lengkap' => ['required', 'string', 'max:255'],
            'nip_nidn' => ['nullable', 'string', 'max:50'],
            'unit_kerja' => ['required', 'string', 'max:255'],
            'spesialisasi' => ['nullable', 'string', 'max:255'],
            'jenis_penguji' => ['required', 'string', 'max:100'],
            'no_sertifikat' => ['nullable', 'string', 'max:100'],
            'status' => ['required', 'string', 'max:50'],
            'email' => ['nullable', 'email', 'max:255'],
            'no_hp' => ['nullable', 'string', 'max:30'],
            'keterangan' => ['nullable', 'string'],
        ], [
            'nama_lengkap.required' => 'Nama lengkap penguji wajib diisi.',
            'unit_kerja.required' => 'Unit kerja / asal institusi wajib diisi.',
            'jenis_penguji.required' => 'Jenis penguji wajib dipilih.',
            'status.required' => 'Status keaktifan penguji wajib dipilih.',
            'email.email' => 'Format email tidak valid.',
        ]);

        Penguji::create($validated);

        return redirect()->route('admin.penguji.index')
            ->with('success', 'Data penguji berhasil ditambahkan!');
    }

    public function show(Penguji $penguji)
    {
        return view('admin.penguji.show', compact('penguji'));
    }

    public function edit(Penguji $penguji)
    {
        return view('admin.penguji.edit', compact('penguji'));
    }

    public function update(Request $request, Penguji $penguji)
    {
        $validated = $request->validate([
            'nama_lengkap' => ['required', 'string', 'max:255'],
            'nip_nidn' => ['nullable', 'string', 'max:50'],
            'unit_kerja' => ['required', 'string', 'max:255'],
            'spesialisasi' => ['nullable', 'string', 'max:255'],
            'jenis_penguji' => ['required', 'string', 'max:100'],
            'no_sertifikat' => ['nullable', 'string', 'max:100'],
            'status' => ['required', 'string', 'max:50'],
            'email' => ['nullable', 'email', 'max:255'],
            'no_hp' => ['nullable', 'string', 'max:30'],
            'keterangan' => ['nullable', 'string'],
        ], [
            'nama_lengkap.required' => 'Nama lengkap penguji wajib diisi.',
            'unit_kerja.required' => 'Unit kerja / asal institusi wajib diisi.',
            'jenis_penguji.required' => 'Jenis penguji wajib dipilih.',
            'status.required' => 'Status keaktifan penguji wajib dipilih.',
            'email.email' => 'Format email tidak valid.',
        ]);

        $penguji->update($validated);

        return redirect()->route('admin.penguji.index')
            ->with('success', 'Data penguji berhasil diperbarui!');
    }

    public function destroy(Penguji $penguji)
    {
        $nama = $penguji->nama_lengkap;
        $penguji->delete();

        return redirect()->route('admin.penguji.index')
            ->with('success', 'Data penguji "' . $nama . '" berhasil dipindahkan ke Kotak Sampah (Trash)!');
    }
}

