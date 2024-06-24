<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prodi extends Model
{
    protected $fillable = ['kopro', 'nama_prodi', 'inisial', 'jenjang'];
}
