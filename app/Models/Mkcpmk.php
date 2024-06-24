<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mkcpmk extends Model
{
    protected $fillable = ['id', 'matkul_id', 'subcpl_id', 'kode_cpmk', 'desk_cpmk'];
}
