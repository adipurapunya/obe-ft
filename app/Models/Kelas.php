<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    protected $fillable = [
        'matkul_id', 'nama_kelas', 'urut', 'kode_kelas', 'semester_id',
        'dosen_satu', 'dosen_dua', 'dosen_tiga', 'dosen_empat', 'dosen_inputnilai'
    ];

    public function matkul() {
        return $this->belongsTo(Matkul::class, 'matkul_id');
    }

    public function mahasiswa()
    {
    return $this->belongsToMany(Mahasiswa::class, 'kelas_mahasiswa', 'kelas_id', 'mahasiswa_id');
    }


    public function semester() {
        return $this->belongsTo(Semester::class, 'semester_id');
    }

    public function dosenSatu()
    {
        return $this->belongsTo(Dosen::class, 'dosen_satu');
    }

    public function dosenDua()
    {
        return $this->belongsTo(Dosen::class, 'dosen_dua');
    }

    public function dosenTiga()
    {
        return $this->belongsTo(Dosen::class, 'dosen_tiga');
    }

    public function dosenEmpat()
    {
        return $this->belongsTo(Dosen::class, 'dosen_empat');
    }


}
