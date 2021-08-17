<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            RoomSeeder::class,
            DaysCombinationSeeder::class,
            CustomerSeeder::class,
            ProfessorSeeder::class,
            RoomclassSeeder::class,
            RoomclassCustomerSeeder::class,
        ]);
    }
}
