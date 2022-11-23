<?php

namespace Database\Factories;

use App\Models\Pays;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ClientFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id'     => User::factory(),
            'pays_id'     => function (array $attributes) { return User::find($attributes['user_id'])->pays_register_id; },
            'nom'         => $this->faker->firstName(),
            'prenoms'     => $this->faker->lastName(),
            'code_postal' => random_int(700, 999999),
            'ville'       => $this->faker->city(),
            'email'       => function (array $attributes) { return User::find($attributes['user_id'])->email; },
            'telephone'   => function (array $attributes) { return User::find($attributes['user_id'])->telephone; },
        ];
    }
}
