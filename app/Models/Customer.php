<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = ['number', 'full_name', 'address', 'phone_number', 'profession', 'is_up_to_date'];

    public function roomclasses()
    {
        return $this->belongsToMany(Roomclass::class, 'roomclass_customer');
    }
}
