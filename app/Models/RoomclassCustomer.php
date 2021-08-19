<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoomclassCustomer extends Model
{
    protected $table = 'roomclass_customer';
    protected $fillable = ['customer_id', 'roomclass_id'];

    use HasFactory;
}
