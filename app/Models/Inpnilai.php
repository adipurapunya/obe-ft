<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inpnilai extends Model
{
    protected $fillable = ['rubnilai_id','mahasiswa_id','kompnilai_id','nilai','nilai_rata'];
}
