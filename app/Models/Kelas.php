<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    public function semester()
    {
        return $this->belongsTo(Semester::class, 'semester_id');
    }
}
