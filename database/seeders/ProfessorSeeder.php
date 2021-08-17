<?php

namespace Database\Seeders;

use App\Models\Professor;
use Illuminate\Database\Seeder;

class ProfessorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Professor::factory()
            ->count(50)
            ->create();
    }
}
