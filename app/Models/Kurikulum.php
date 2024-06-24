<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kurikulum extends Model
{
    protected $fillable = ['id', 'prodi_id', 'kode_kuri', 'nama_kuri', 'tahun_kuri', 'deskripsi', 'sk_kuri', 'filesk'];
}
