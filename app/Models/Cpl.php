<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cpl extends Model
{
    protected $fillable = ['id', 'prodi_id', 'kurikulum_id', 'kode_cpl', 'deskrip_cpl'];
}
