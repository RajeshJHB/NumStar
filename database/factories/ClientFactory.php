<?php

namespace Database\Factories;

use App\Models\Client;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Client>
 */
class ClientFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = Client::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        $date = $this->faker->date();
        $time = $this->faker->time('H:i:s');

        return [
            'user_id' => User::factory(),
            'name' => $this->faker->firstName(),
            'middle_name' => $this->faker->optional()->firstName(),
            'surname' => $this->faker->lastName(),
            'date_of_birth' => $date,
            'time_of_birth' => $time,
            'sex' => $this->faker->randomElement(['male', 'female', 'na']),
            'country_of_birth' => $this->faker->country(),
            'town_of_birth' => $this->faker->city(),
            'save_as' => $this->faker->unique()->userName(),
        ];
    }
}
