<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mksubcpmk extends Model
{
    protected $fillable = ['id', 'matkul_id', 'mkcpmk_id', 'kode_scpmk', 'desk_scpmk'];
}
