<?php

namespace App\Http\Controllers;

use App\Models\PengajuanPerubahan;
use App\Models\Dosen;
use App\Models\Eksternal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class VerifikasiController extends Controller
{
    /**
     * Daftar pengajuan perubahan data
     */
    public function index(Request $request)
    {
        $status = $request->query('status', 'pending');
        $tipe = $request->query('tipe');
        $cari = $request->query('cari');

        $query = PengajuanPerubahan::with('verifier')->latest();

        if ($status && in_array($status, ['pending', 'approved', 'rejected'])) {
            $query->where('status', $status);
        }

        if ($tipe) {
            $query->where('tipe', $tipe);
        }

        if ($cari) {
            $query->where(function ($q) use ($cari) {
                $q->where('nama_pengaju', 'like', "%{$cari}%")
                  ->orWhere('email_pengaju', 'like', "%{$cari}%")
                  ->orWhere('no_hp_pengaju', 'like', "%{$cari}%")
                  ->orWhere('alasan_perubahan', 'like', "%{$cari}%");
            });
        }

        $pengajuans = $query->paginate(15)->withQueryString();

        $stats = [
            'total' => PengajuanPerubahan::count(),
            'pending' => PengajuanPerubahan::where('status', 'pending')->count(),
            'approved' => PengajuanPerubahan::where('status', 'approved')->count(),
            'rejected' => PengajuanPerubahan::where('status', 'rejected')->count(),
        ];

        return view('admin.verifikasi.index', compact('pengajuans', 'stats', 'status', 'tipe', 'cari'));
    }

    /**
     * Tinjau detail pengajuan dan komparasi diff data
     */
    public function show(PengajuanPerubahan $pengajuan)
    {
        $target = $pengajuan->getTargetRecord();
        $diff = $pengajuan->getDiff();

        return view('admin.verifikasi.show', compact('pengajuan', 'target', 'diff'));
    }

    /**
     * Setujui pengajuan dan terapkan perubahan ke data utama
     */
    public function approve(Request $request, PengajuanPerubahan $pengajuan)
    {
        if ($pengajuan->status === 'approved') {
            return redirect()->route('admin.verifikasi.show', $pengajuan)
                ->with('error', 'Pengajuan ini sudah disetujui sebelumnya.');
        }

        $target = $pengajuan->getTargetRecord();
        $dataBaru = $pengajuan->data_baru ?? [];

        if ($target) {
            // Filter hanya field yang boleh di-update
            $fillable = $target->getFillable();
            $updateData = array_intersect_key($dataBaru, array_flip($fillable));

            // Bersihkan string kosong menjadi null bila perlu
            foreach ($updateData as $key => $val) {
                if ($val === '') {
                    $updateData[$key] = null;
                }
            }

            $target->update($updateData);
        } else {
            // Jika record target belum ada (misal pengajuan data baru)
            if ($pengajuan->tipe === 'dosen') {
                Dosen::create($dataBaru);
            } elseif ($pengajuan->tipe === 'eksternal') {
                Eksternal::create($dataBaru);
            }
        }

        $pengajuan->update([
            'status' => 'approved',
            'catatan_admin' => $request->input('catatan_admin'),
            'verified_by' => Auth::id(),
            'verified_at' => now(),
        ]);

        return redirect()->route('admin.verifikasi.index')
            ->with('success', 'Pengajuan perubahan data berhasil disetujui! Data master telah otomatis diperbarui.');
    }

    /**
     * Tolak pengajuan perubahan data
     */
    public function reject(Request $request, PengajuanPerubahan $pengajuan)
    {
        $request->validate([
            'catatan_admin' => ['required', 'string', 'max:1000'],
        ], [
            'catatan_admin.required' => 'Alasan / catatan penolakan wajib diisi untuk transparansi.',
        ]);

        $pengajuan->update([
            'status' => 'rejected',
            'catatan_admin' => $request->input('catatan_admin'),
            'verified_by' => Auth::id(),
            'verified_at' => now(),
        ]);

        return redirect()->route('admin.verifikasi.index')
            ->with('success', 'Pengajuan perubahan data telah ditolak.');
    }

    /**
     * Hapus riwayat pengajuan
     */
    public function destroy(PengajuanPerubahan $pengajuan)
    {
        if ($pengajuan->dokumen_pendukung) {
            Storage::disk('public')->delete($pengajuan->dokumen_pendukung);
        }

        $pengajuan->delete();

        return redirect()->route('admin.verifikasi.index')
            ->with('success', 'Riwayat pengajuan berhasil dihapus.');
    }
}
