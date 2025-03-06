<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Matkul extends Model
{
    use HasFactory;
    protected $table = 'matkuls';
    protected $fillable = ['kurikulum_id', 'prodi_id', 'semester_id', 'kode_mk', 'nama_mk',
                            'status', 'rumpun_mk', 'kurikulum_id', 'sks_kuri', 'sks_teo',
                            'sks_prak', 'sks_lap'];

    public function Rubnilai()
    {
        return $this->hasManyThrough(
            RubNilai::class,
            Mkcpmk::class,
            'matkul_id',
            'mkcpmk_id',
            'id',
            'id'
        );
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }
}
