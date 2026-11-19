<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Eksternal extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'eksternals';

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
        'asal_institusi',
        'pangkat_gol',
        'pendidikan_terakhir',
        'bagian',
        'email',
        'no_hp',
        'bidang_keahlian',
    ];

    /**
     * Scope untuk pencarian universal data eksternal
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
              ->orWhere('asal_institusi', 'like', "%{$search}%")
              ->orWhere('bagian', 'like', "%{$search}%")
              ->orWhere('pangkat_gol', 'like', "%{$search}%")
              ->orWhere('bidang_keahlian', 'like', "%{$search}%");
        });
    }
}

