<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Semester extends Model
{
    protected $fillable = ['id', 'nama_smtr', 'semester', 'status', 'tahun', 'keterangan'];
}
