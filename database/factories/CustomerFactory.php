<?php

namespace Database\Factories;

require_once __DIR__ . '../../../app/helpers/helpers.php';

use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;

use function App\Helpers\addZerosToLeft;

class CustomerFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Customer::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'number' => addZerosToLeft((string)$this->faker->numberBetween(1, 9999999999), 10),
            'full_name' => $this->faker->name(),
            'address' => $this->faker->streetAddress(),
            'phone_number' => $this->faker->e164PhoneNumber(),
            'profession' => $this->faker->word(),
            'is_up_to_date' => (bool)$this->faker->numberBetween(0, 1),
        ];
    }
}
