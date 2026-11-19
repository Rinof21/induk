<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Dosen extends Model
{
    use HasFactory, SoftDeletes;

    public const DAFTAR_BAGIAN = [
        'Bagian Kedokteran',
        'Bagian Farmasi',
        'Bagian Keperawatan',
    ];

    protected $fillable = [
        'nama_lengkap',
        'nip',
        'nuptk',
        'nik',
        'npwp',
        'pangkat_gol',
        'jafung',
        'pendidikan_terakhir',
        'bagian',
        'status_ikatan_kerja',
        'status_tugas',
        'email',
        'no_hp',
        'bidang_keahlian',
    ];

    // Accessor & Mutator untuk kompatibilitas jika masih ada yang memanggil unit_kerja
    public function getUnitKerjaAttribute()
    {
        return $this->bagian;
    }

    public function setUnitKerjaAttribute($value)
    {
        $this->attributes['bagian'] = $value;
    }

    /**
     * Scope untuk pencarian universal
     */
    public function scopeSearch($query, $search)
    {
        if (empty($search)) {
            return $query;
        }

        return $query->where(function ($q) use ($search) {
            $q->where('nama_lengkap', 'like', "%{$search}%")
              ->orWhere('nip', 'like', "%{$search}%")
              ->orWhere('nuptk', 'like', "%{$search}%")
              ->orWhere('nik', 'like', "%{$search}%")
              ->orWhere('npwp', 'like', "%{$search}%")
              ->orWhere('bagian', 'like', "%{$search}%")
              ->orWhere('pangkat_gol', 'like', "%{$search}%")
              ->orWhere('jafung', 'like', "%{$search}%")
              ->orWhere('bidang_keahlian', 'like', "%{$search}%");
        });
    }
}
