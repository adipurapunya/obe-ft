<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MkSubCpl extends Model
{
    protected $table = 'mkscpls';
    protected $fillable = ['id', 'matkul_id', 'cpl_id', 'subcpl_id'];

    public function subcpl()
    {
        return $this->belongsTo(SubCpl::class, 'subcpl_id'); // Sesuaikan nama model SubCpl dan foreign key
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'matkul_id'); // Sesuaikan nama model Kelas dan foreign key
    }

    public function matkul()
    {
        return $this->belongsTo(Matkul::class, 'matkul_id'); // Sesuaikan nama model Matkul dan foreign key
    }


}
