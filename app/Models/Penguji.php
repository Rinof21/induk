<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Penguji extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'nama_lengkap',
        'nip_nidn',
        'unit_kerja',
        'spesialisasi',
        'jenis_penguji',
        'no_sertifikat',
        'status',
        'email',
        'no_hp',
        'keterangan',
    ];

    public function scopeSearch($query, $search)
    {
        if (empty($search)) {
            return $query;
        }

        return $query->where(function ($q) use ($search) {
            $q->where('nama_lengkap', 'like', "%{$search}%")
              ->orWhere('nip_nidn', 'like', "%{$search}%")
              ->orWhere('unit_kerja', 'like', "%{$search}%")
              ->orWhere('spesialisasi', 'like', "%{$search}%")
              ->orWhere('jenis_penguji', 'like', "%{$search}%")
              ->orWhere('no_sertifikat', 'like', "%{$search}%");
        });
    }
}

