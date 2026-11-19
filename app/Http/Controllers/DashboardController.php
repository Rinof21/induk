<?php

namespace App\Http\Controllers;

use App\Models\Dosen;
use App\Models\Eksternal;
use App\Models\Penguji;
use App\Models\PasienStandar;
use App\Models\User;
use App\Models\AppSetting;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_dosen' => Dosen::count(),
            'total_guru_besar' => Dosen::where('jafung', 'Guru Besar')->count(),
            'total_tugas_belajar' => Dosen::where('status_tugas', 'Tugas Belajar')->count(),
            'total_eksternal' => Eksternal::count(),
            'total_penguji' => Penguji::count(),
            'total_penguji_aktif' => Penguji::where('status', 'Aktif')->count(),
            'total_pasien_standar' => PasienStandar::count(),
            'total_ps_tersedia' => PasienStandar::where('status_ketersediaan', 'Tersedia')->count(),
            'total_users' => User::count(),
            'total_trash' => Dosen::onlyTrashed()->count() + Eksternal::onlyTrashed()->count() + Penguji::onlyTrashed()->count() + PasienStandar::onlyTrashed()->count() + User::onlyTrashed()->count(),
        ];

        $tokenEnabled = AppSetting::getVal('public_token_enabled', '0') === '1';
        $currentToken = AppSetting::getVal('public_access_token', 'kitasehat');

        $latestDosens = Dosen::latest()->take(5)->get();
        $latestEksternals = Eksternal::latest()->take(5)->get();
        $latestPengujis = Penguji::latest()->take(5)->get();
        $latestPasiens = PasienStandar::latest()->take(5)->get();

        return view('admin.dashboard', compact('stats', 'tokenEnabled', 'currentToken', 'latestDosens', 'latestEksternals', 'latestPengujis', 'latestPasiens'));
    }
}

