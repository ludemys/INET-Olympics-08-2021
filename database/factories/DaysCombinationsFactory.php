<?php

namespace Database\Factories;

use App\Models\DaysCombinations;
use Illuminate\Database\Eloquent\Factories\Factory;

class DaysCombinationsFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = DaysCombinations::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $days =
            ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'][rand(0, 6)] . '-' .
            ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'][rand(0, 6)];
        return [
            'days' => $days
        ];
    }
}
