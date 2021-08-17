<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Professor extends Model
{
    use HasFactory;

    protected $fillable = ['full_name', 'dni', 'phone_number', 'birthdate', 'entry_date'];

    public function roomclasses()
    {
        return $this->hasMany(Roomclass::class, 'roomclasses_id');
    }
}
