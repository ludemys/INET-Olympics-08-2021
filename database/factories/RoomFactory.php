<?php

namespace Database\Factories;

require_once __DIR__ . '../../../app/helpers/helpers.php';

use App\Models\Room;
use Illuminate\Database\Eloquent\Factories\Factory;

use function App\Helpers\addZerosToLeft;

class RoomFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Room::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'number' => addZerosToLeft((string)$this->faker->numberBetween(1, 999), 3),
            'size' => $this->faker->randomFloat(2, 1, 99),
            'location' => ['front', 'back', 'hall', 'front hall', 'back hall', 'rooftop', 'basement', 'corridor', 'left corridor', 'right corridor'][rand(0, 9)],
            'type' => $this->faker->words(1, true),
        ];
    }
}
