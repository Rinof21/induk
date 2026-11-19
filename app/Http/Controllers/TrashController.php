<?php

namespace App\Http\Controllers;

use App\Models\Dosen;
use App\Models\Eksternal;
use App\Models\Penguji;
use App\Models\PasienStandar;
use App\Models\User;
use Illuminate\Http\Request;

class TrashController extends Controller
{
    /**
     * Peta tipe ke Model Class
     */
    protected array $modelMap = [
        'dosen' => Dosen::class,
        'eksternal' => Eksternal::class,
        'penguji' => Penguji::class,
        'pasien_standar' => PasienStandar::class,
        'users' => User::class,
    ];

    /**
     * Label Bahasa Indonesia untuk setiap modul
     */
    protected array $labelMap = [
        'dosen' => 'Dosen Internal',
        'eksternal' => 'Data Eksternal',
        'penguji' => 'Data Penguji',
        'pasien_standar' => 'Pasien Standar',
        'users' => 'Pengguna & Akun',
    ];

    public function index(Request $request)
    {
        $tipe = $request->query('tipe', 'dosen');
        if (!array_key_exists($tipe, $this->modelMap)) {
            $tipe = 'dosen';
        }

        $search = $request->query('cari');

        // Hitung jumlah item di sampah untuk setiap modul
        $counts = [
            'dosen' => Dosen::onlyTrashed()->count(),
            'eksternal' => Eksternal::onlyTrashed()->count(),
            'penguji' => Penguji::onlyTrashed()->count(),
            'pasien_standar' => PasienStandar::onlyTrashed()->count(),
            'users' => User::onlyTrashed()->count(),
        ];
        $totalTrash = array_sum($counts);

        $modelClass = $this->modelMap[$tipe];
        $query = $modelClass::onlyTrashed();

        if (!empty($search)) {
            if ($tipe === 'dosen' || $tipe === 'eksternal' || $tipe === 'penguji') {
                $query->search($search);
            } elseif ($tipe === 'pasien_standar') {
                $query->where(function ($q) use ($search) {
                    $q->where('nama_lengkap', 'like', "%{$search}%")
                      ->orWhere('kode_ps', 'like', "%{$search}%")
                      ->orWhere('pekerjaan', 'like', "%{$search}%");
                });
            } elseif ($tipe === 'users') {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%")
                      ->orWhere('fakultas', 'like', "%{$search}%");
                });
            }
        }

        $items = $query->orderBy('deleted_at', 'desc')->paginate(15)->withQueryString();

        return view('admin.trash.index', [
            'tipe' => $tipe,
            'labelTipe' => $this->labelMap[$tipe],
            'items' => $items,
            'counts' => $counts,
            'totalTrash' => $totalTrash,
            'labelMap' => $this->labelMap,
        ]);
    }

    /**
     * Pulihkan satu rekaman data dari sampah
     */
    public function restore(string $tipe, $id)
    {
        if (!array_key_exists($tipe, $this->modelMap)) {
            return back()->with('error', 'Kategori data tidak valid.');
        }

        $modelClass = $this->modelMap[$tipe];
        $item = $modelClass::onlyTrashed()->findOrFail($id);
        $nama = $item->nama_lengkap ?? $item->name ?? 'Data';
        $item->restore();

        return redirect()->route('admin.trash.index', ['tipe' => $tipe])
            ->with('success', "{$this->labelMap[$tipe]} \"{$nama}\" berhasil dipulihkan!");
    }

    /**
     * Hapus permanen satu rekaman data
     */
    public function forceDelete(string $tipe, $id)
    {
        if (!array_key_exists($tipe, $this->modelMap)) {
            return back()->with('error', 'Kategori data tidak valid.');
        }

        $modelClass = $this->modelMap[$tipe];
        $item = $modelClass::onlyTrashed()->findOrFail($id);
        $nama = $item->nama_lengkap ?? $item->name ?? 'Data';
        $item->forceDelete();

        return redirect()->route('admin.trash.index', ['tipe' => $tipe])
            ->with('success', "{$this->labelMap[$tipe]} \"{$nama}\" berhasil dihapus secara permanen dari database!");
    }

    /**
     * Pulihkan semua rekaman data untuk tipe yang dipilih
     */
    public function restoreAll(string $tipe)
    {
        if (!array_key_exists($tipe, $this->modelMap)) {
            return back()->with('error', 'Kategori data tidak valid.');
        }

        $modelClass = $this->modelMap[$tipe];
        $count = $modelClass::onlyTrashed()->count();
        $modelClass::onlyTrashed()->restore();

        return redirect()->route('admin.trash.index', ['tipe' => $tipe])
            ->with('success', "Semua {$this->labelMap[$tipe]} di kotak sampah ({$count} data) berhasil dipulihkan!");
    }

    /**
     * Kosongkan tempat sampah untuk tipe tertentu
     */
    public function emptyTrash(string $tipe)
    {
        if (!array_key_exists($tipe, $this->modelMap)) {
            return back()->with('error', 'Kategori data tidak valid.');
        }

        $modelClass = $this->modelMap[$tipe];
        $count = $modelClass::onlyTrashed()->count();
        $modelClass::onlyTrashed()->forceDelete();

        return redirect()->route('admin.trash.index', ['tipe' => $tipe])
            ->with('success', "Kotak sampah {$this->labelMap[$tipe]} ({$count} data) berhasil dikosongkan secara permanen!");
    }
}

