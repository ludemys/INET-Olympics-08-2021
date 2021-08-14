<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Roomclass extends Model
{
    use HasFactory;

    public function daysCombination()
    {
        return $this->hasOne(DaysCombinations::class, 'days_combination_id');
    }
    public function room()
    {
        return $this->hasOne(Room::class, 'room_id');
    }

    public function customers()
    {
        return $this->belongsToMany(Customer::class, 'roomclass_customer');
    }
}
