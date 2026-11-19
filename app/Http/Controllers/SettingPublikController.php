<?php

namespace App\Http\Controllers;

use App\Models\AppSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SettingPublikController extends Controller
{
    public function index()
    {
        $tokenEnabled = AppSetting::getVal('public_token_enabled', '0') === '1';
        $currentToken = AppSetting::getVal('public_access_token', 'kitasehat');
        $tokenMessage = AppSetting::getVal('public_token_message', 'Portal Data Terintegrasi Dosen, Penguji, dan Pasien Standar. Masukkan token akses untuk melihat data.');
        $instansiNama = AppSetting::getVal('instansi_nama', 'Bagian Hukum dan Kepegawaian & Fakultas Terkait');
        $universitasNama = AppSetting::getVal('universitas_nama', 'Universitas Tanjungpura');

        // URL langsung dengan token
        $shareableUrl = route('publik.dosen', ['token' => $currentToken]);

        return view('admin.setting_publik.index', compact(
            'tokenEnabled',
            'currentToken',
            'tokenMessage',
            'instansiNama',
            'universitasNama',
            'shareableUrl'
        ));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'public_token_enabled' => ['required', 'in:0,1'],
            'public_access_token' => ['required', 'string', 'min:4', 'max:100'],
            'public_token_message' => ['nullable', 'string', 'max:1000'],
            'instansi_nama' => ['nullable', 'string', 'max:255'],
            'universitas_nama' => ['nullable', 'string', 'max:255'],
        ], [
            'public_access_token.required' => 'Token akses publik tidak boleh kosong.',
            'public_access_token.min' => 'Token akses minimal 4 karakter.',
        ]);

        AppSetting::setVal('public_token_enabled', $validated['public_token_enabled']);
        AppSetting::setVal('public_access_token', trim($validated['public_access_token']));
        AppSetting::setVal('public_token_message', $validated['public_token_message'] ?? '');
        if (!empty($validated['instansi_nama'])) {
            AppSetting::setVal('instansi_nama', $validated['instansi_nama']);
        }
        if (!empty($validated['universitas_nama'])) {
            AppSetting::setVal('universitas_nama', $validated['universitas_nama']);
        }

        $statusText = $validated['public_token_enabled'] == '1' ? 'diaktifkan' : 'dinonaktifkan (portal publik bebas diakses)';

        return redirect()->route('admin.pengaturan-publik.index')
            ->with('success', 'Pengaturan akses token halaman publik berhasil diperbarui! Proteksi token saat ini: ' . $statusText);
    }

    /**
     * Generate Token Baru Otomatis
     */
    public function generateToken()
    {
        $newToken = 'UNTAN-' . strtoupper(Str::random(6)) . '-' . date('Y');
        AppSetting::setVal('public_access_token', $newToken);

        return redirect()->route('admin.pengaturan-publik.index')
            ->with('success', 'Token baru berhasil dibuat: ' . $newToken);
    }

    /**
     * Toggle cepat status token (Aktif / Nonaktif)
     */
    public function toggle()
    {
        $currentState = AppSetting::getVal('public_token_enabled', '0');
        $newState = ($currentState === '1') ? '0' : '1';
        AppSetting::setVal('public_token_enabled', $newState);

        $msg = ($newState === '1') 
            ? 'Proteksi token publik telah DIAKTIFKAN. Pengunjung harus memasukkan token untuk mengakses.'
            : 'Proteksi token publik telah DINONAKTIFKAN. Semua bagian fakultas dapat langsung mengakses data.';

        return back()->with('success', $msg);
    }
}

