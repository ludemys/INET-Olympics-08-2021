<?php

namespace Database\Factories;

use App\Models\RoomclassCustomer;
use Illuminate\Database\Eloquent\Factories\Factory;

class RoomclassCustomerFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = RoomclassCustomer::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'roomclass_id' => $this->faker->numberBetween(1, 20),
            'customer_id' => $this->faker->numberBetween(1, 20),
        ];
    }
}
