<?php

namespace Database\Seeders;

use App\Models\DaysCombinations;
use Illuminate\Database\Seeder;

class DaysCombinationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DaysCombinations::factory()
            ->count(50)
            ->create();
    }
}
