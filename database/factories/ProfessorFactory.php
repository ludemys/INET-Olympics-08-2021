<?php

namespace Database\Factories;

use App\Models\Professor;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProfessorFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Professor::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'full_name' => $this->faker->name(),
            'dni' => $this->faker->numberBetween(1000000, 99999999),
            'phone_number' => $this->faker->e164PhoneNumber(),
            'birthdate' => $this->faker->date(),
            'entry_date' => $this->faker->date(),
        ];
    }
}
