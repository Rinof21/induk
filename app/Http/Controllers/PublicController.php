<?php

namespace App\Http\Controllers;

use App\Models\Dosen;
use App\Models\Eksternal;
use App\Models\Penguji;
use App\Models\PasienStandar;
use App\Models\AppSetting;
use Illuminate\Http\Request;

class PublicController extends Controller
{
    /**
     * Tampilkan Portal Publik - Tab Data Dosen
     */
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

        // Statistik ringkasan untuk kartu metric seperti di gambar
        $stats = [
            'total_dosen' => Dosen::count(),
            'total_guru_besar' => Dosen::where('jafung', 'Guru Besar')->count(),
            'total_tugas_belajar' => Dosen::where('status_tugas', 'Tugas Belajar')->count(),
            'pembaruan_terakhir' => Dosen::latest('updated_at')->first()?->updated_at?->translatedFormat('d/m/Y H:i') ?? date('d/m/Y H:i'),
            'total_penguji' => Penguji::count(),
            'total_pasien_standar' => PasienStandar::count(),
        ];

        // Daftar Bagian Internal Fakultas (Bagian Kedokteran, Bagian Farmasi, Bagian Keperawatan)
        $daftarBagian = Dosen::DAFTAR_BAGIAN;
        $daftarJafung = ['Tenaga Pengajar', 'Asisten Ahli', 'Lektor', 'Lektor Kepala', 'Guru Besar'];

        $tokenProtected = AppSetting::getVal('public_token_enabled', '0') === '1';

        return view('public.dosen', compact('dosens', 'stats', 'daftarBagian', 'daftarJafung', 'tokenProtected'));
    }

    /**
     * Tampilkan Portal Publik - Tab Data Eksternal
     */
    public function eksternal(Request $request)
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

        $stats = [
            'total_eksternal' => Eksternal::count(),
            'total_spesialis' => Eksternal::whereIn('pendidikan_terakhir', ['Sp-1', 'Sp-2', 'Subspesialis'])->count(),
            'total_s3' => Eksternal::where('pendidikan_terakhir', 'S3')->count(),
            'pembaruan_terakhir' => Eksternal::latest('updated_at')->first()?->updated_at?->translatedFormat('d/m/Y H:i') ?? date('d/m/Y H:i'),
        ];

        $daftarInstitusi = Eksternal::select('asal_institusi')->whereNotNull('asal_institusi')->distinct()->pluck('asal_institusi');
        $daftarBagian = Eksternal::DAFTAR_BAGIAN;
        $tokenProtected = AppSetting::getVal('public_token_enabled', '0') === '1';

        return view('public.eksternal', compact('eksternals', 'stats', 'daftarInstitusi', 'daftarBagian', 'tokenProtected'));
    }

    /**
     * Tampilkan Portal Publik - Tab Data Penguji
     */
    public function penguji(Request $request)
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

        $stats = [
            'total_penguji' => Penguji::count(),
            'total_aktif' => Penguji::where('status', 'Aktif')->count(),
            'total_osce' => Penguji::where('jenis_penguji', 'like', '%OSCE%')->count(),
            'total_sidang' => Penguji::where('jenis_penguji', 'like', '%Skripsi%')->orWhere('jenis_penguji', 'like', '%Sidang%')->count(),
            'pembaruan_terakhir' => Penguji::latest('updated_at')->first()?->updated_at?->translatedFormat('d/m/Y H:i') ?? date('d/m/Y H:i'),
        ];

        $daftarJenisPenguji = Penguji::select('jenis_penguji')->distinct()->pluck('jenis_penguji');
        $tokenProtected = AppSetting::getVal('public_token_enabled', '0') === '1';

        return view('public.penguji', compact('pengujis', 'stats', 'daftarJenisPenguji', 'tokenProtected'));
    }

    /**
     * Tampilkan Portal Publik - Tab Data Pasien Standar
     */
    public function pasienStandar(Request $request)
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

        $stats = [
            'total_ps' => PasienStandar::count(),
            'total_tersedia' => PasienStandar::where('status_ketersediaan', 'Tersedia')->count(),
            'total_bertugas' => PasienStandar::where('status_ketersediaan', 'Sedang Bertugas')->count(),
            'total_laki' => PasienStandar::where('jenis_kelamin', 'Laki-laki')->count(),
            'total_perempuan' => PasienStandar::where('jenis_kelamin', 'Perempuan')->count(),
            'pembaruan_terakhir' => PasienStandar::latest('updated_at')->first()?->updated_at?->translatedFormat('d/m/Y H:i') ?? date('d/m/Y H:i'),
        ];

        $tokenProtected = AppSetting::getVal('public_token_enabled', '0') === '1';

        return view('public.pasien_standar', compact('pasiens', 'stats', 'tokenProtected'));
    }

    /**
     * Halaman Gerbang Token Akses
     */
    public function tokenGate(Request $request)
    {
        // Jika token tidak diaktifkan, langsung redirect ke portal
        $tokenEnabled = AppSetting::getVal('public_token_enabled', '0');
        if ($tokenEnabled !== '1' && $tokenEnabled !== 1) {
            return redirect()->route('publik.dosen');
        }

        // Jika sudah terotentikasi publik atau login admin
        if ($request->session()->get('public_authenticated') === true || auth()->check()) {
            return redirect()->route('publik.dosen');
        }

        $message = AppSetting::getVal('public_token_message', 'Silakan masukkan token akses resmi yang telah diberikan oleh pihak universitas/fakultas.');
        $instansi = AppSetting::getVal('instansi_nama', 'Bagian Hukum dan Kepegawaian & Fakultas Terkait');
        $universitas = AppSetting::getVal('universitas_nama', 'Universitas Tanjungpura');

        return view('public.token_gate', compact('message', 'instansi', 'universitas'));
    }

    /**
     * Verifikasi Token Akses yang dimasukkan pengunjung
     */
    public function verifyToken(Request $request)
    {
        $request->validate([
            'token' => ['required', 'string'],
        ], [
            'token.required' => 'Token akses wajib dimasukkan.',
        ]);

        $inputToken = trim($request->input('token'));
        $validToken = AppSetting::getVal('public_access_token', 'kitasehat');

        if (strcasecmp($validToken, $inputToken) === 0) {
            $request->session()->put('public_authenticated', true);
            return redirect()->route('publik.dosen')->with('success', 'Token valid! Selamat datang di Portal Akses Fakultas.');
        }

        return back()->withErrors([
            'token' => 'Token akses yang Anda masukkan tidak valid atau telah diperbarui. Silakan hubungi bagian administrator fakultas.',
        ]);
    }

    /**
     * Keluar dari sesi token publik
     */
    public function logoutToken(Request $request)
    {
        $request->session()->forget('public_authenticated');
        return redirect()->route('publik.token.gate')->with('info', 'Sesi akses publik Anda telah diakhiri.');
    }
}

