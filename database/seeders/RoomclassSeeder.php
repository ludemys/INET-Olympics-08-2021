<?php

namespace Database\Seeders;

use App\Models\Roomclass;
use Illuminate\Database\Seeder;

class RoomclassSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Roomclass::factory()
            ->count(50)
            ->create();
    }
}
