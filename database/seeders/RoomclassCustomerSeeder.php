<?php

namespace Database\Seeders;

use App\Models\RoomclassCustomer;
use Illuminate\Database\Seeder;

class RoomclassCustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        RoomclassCustomer::factory()
            ->count(50)
            ->create();
    }
}
