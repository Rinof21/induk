<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengajuanPerubahan extends Model
{
    use HasFactory;

    protected $table = 'pengajuan_perubahans';

    protected $fillable = [
        'tipe',
        'record_id',
        'nama_pengaju',
        'email_pengaju',
        'no_hp_pengaju',
        'alasan_perubahan',
        'data_lama',
        'data_baru',
        'dokumen_pendukung',
        'status',
        'catatan_admin',
        'verified_by',
        'verified_at',
    ];

    protected $casts = [
        'data_lama' => 'array',
        'data_baru' => 'array',
        'verified_at' => 'datetime',
    ];

    public const FIELD_LABELS = [
        'nama_lengkap' => 'Nama Lengkap',
        'nip' => 'NIP',
        'nuptk' => 'NUPTK / NIDN',
        'nik' => 'NIK (Kependudukan)',
        'npwp' => 'NPWP',
        'bagian' => 'Bagian Internal Fakultas',
        'pangkat_gol' => 'Pangkat / Golongan Ruang',
        'jafung' => 'Jabatan Fungsional',
        'pendidikan_terakhir' => 'Pendidikan Terakhir',
        'status_ikatan_kerja' => 'Status Ikatan Kerja',
        'status_tugas' => 'Status Tugas / Keaktifan',
        'asal_institusi' => 'Asal Institusi / RS',
        'email' => 'Email',
        'no_hp' => 'No. HP / WhatsApp',
        'bidang_keahlian' => 'Bidang Keahlian / Minat',
    ];

    /**
     * Relasi ke admin yang memverifikasi
     */
    public function verifier()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    /**
     * Mengambil model asli data target
     */
    public function getTargetRecord()
    {
        if ($this->tipe === 'eksternal') {
            return Eksternal::withTrashed()->find($this->record_id);
        }

        return Dosen::withTrashed()->find($this->record_id);
    }

    /**
     * Mendapatkan nama target
     */
    public function getTargetNameAttribute()
    {
        $target = $this->getTargetRecord();
        return $target ? $target->nama_lengkap : ($this->data_lama['nama_lengkap'] ?? $this->data_baru['nama_lengkap'] ?? 'Data Baru');
    }

    /**
     * Menghitung perbandingan field lama vs baru
     */
    public function getDiff(): array
    {
        $diff = [];
        $lama = $this->data_lama ?? [];
        $baru = $this->data_baru ?? [];

        $allKeys = array_unique(array_merge(array_keys($lama), array_keys($baru)));

        foreach ($allKeys as $key) {
            $valLama = trim((string) ($lama[$key] ?? ''));
            $valBaru = trim((string) ($baru[$key] ?? ''));
            $isChanged = ($valLama !== $valBaru);

            $diff[$key] = [
                'label' => self::FIELD_LABELS[$key] ?? ucwords(str_replace('_', ' ', $key)),
                'old' => $valLama !== '' ? $valLama : '-',
                'new' => $valBaru !== '' ? $valBaru : '-',
                'changed' => $isChanged,
            ];
        }

        return $diff;
    }
}
