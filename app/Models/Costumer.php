<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Costumer extends Model
{
    use HasFactory;

    public function roomclasses()
    {
        return $this->belongsToMany(Roomclass::class, 'roomclass_customer');
    }
}
