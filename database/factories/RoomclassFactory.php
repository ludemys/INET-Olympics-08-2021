<?php

namespace Database\Factories;

require_once __DIR__ . '../../../app/helpers/helpers.php';

use App\Models\Roomclass;
use Illuminate\Database\Eloquent\Factories\Factory;

use function App\Helpers\addZerosToLeft;

class RoomclassFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Roomclass::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'number' => addZerosToLeft((string)$this->faker->numberBetween(1, 9999), 4),
            'description' => $this->faker->paragraph(2, true),
            'price' => (float)$this->faker->randomFloat(2, 1, 69),
            'days_combination_id' => $this->faker->numberBetween(1, 20),
            'room_id' => $this->faker->numberBetween(1, 20),
            'professor_id' => $this->faker->numberBetween(1, 20),
        ];
    }
}
