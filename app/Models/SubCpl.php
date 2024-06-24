<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubCpl extends Model
{
    protected $table = 'subcpls';
    protected $fillable = ['id', 'cpl_id', 'kode_subcpl', 'desk_subcpl'];

    public function matkul()
    {
        return $this->belongsTo(Matkul::class, 'matkul_id'); // Sesuaikan nama model Matkul dan foreign key
    }
}
