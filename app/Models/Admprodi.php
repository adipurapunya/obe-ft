<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Admprodi extends Model
{
    use HasFactory;

    protected $fillable = ['id', 'user_id', 'prodi_id', 'koprodi'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function prodi()
    {
        return $this->belongsTo(Prodi::class, 'user_id', 'id');
    }
}
