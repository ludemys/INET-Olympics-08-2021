<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Roomclass extends Model
{
    use HasFactory;

    protected $fillable = ['number', 'description', 'price', 'days_combination_id', 'room_id', 'professor_id'];

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
