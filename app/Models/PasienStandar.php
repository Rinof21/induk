<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PasienStandar extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'pasien_standars';

    protected $fillable = [
        'kode_ps',
        'nama_lengkap',
        'jenis_kelamin',
        'tanggal_lahir',
        'usia',
        'no_hp',
        'alamat',
        'pekerjaan',
        'peran_dikuasai',
        'tinggi_badan',
        'berat_badan',
        'status_ketersediaan',
        'catatan_khusus',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_lahir' => 'date',
        ];
    }

    public function scopeSearch($query, $search)
    {
        if (empty($search)) {
            return $query;
        }

        return $query->where(function ($q) use ($search) {
            $q->where('nama_lengkap', 'like', "%{$search}%")
              ->orWhere('kode_ps', 'like', "%{$search}%")
              ->orWhere('pekerjaan', 'like', "%{$search}%")
              ->orWhere('peran_dikuasai', 'like', "%{$search}%")
              ->orWhere('alamat', 'like', "%{$search}%")
              ->orWhere('catatan_khusus', 'like', "%{$search}%");
        });
    }
}

