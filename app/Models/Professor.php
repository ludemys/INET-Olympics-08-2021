<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Professor extends Model
{
    use HasFactory;

    public function roomclasses()
    {
        return $this->hasMany(Roomclass::class, 'roomclasses_id');
    }
}
