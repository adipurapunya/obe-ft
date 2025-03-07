<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dosen extends Model
{
    protected $fillable = ['id', 'user_id', 'nidn', 'nip', 'nama_dosen', 'prodi_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
