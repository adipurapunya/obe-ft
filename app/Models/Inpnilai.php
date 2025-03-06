<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inpnilai extends Model
{
    use HasFactory;
    protected $fillable = ['rubnilai_id','nim', 'kelas_id', 'kompnilai_id','nilai'];
}
