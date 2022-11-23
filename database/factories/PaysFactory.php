<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class PaysFactory extends Factory
{
    /**
    * Define the model's default state.
    *
    * @return array
    */
    public function definition()
    {
        return [
            'indicatif'       => $this->faker->randomNumber(),
            'nom'             => $this->faker->country(),
            'code'            => $this->faker->countryCode(),
            'monnaie'         => $this->faker->countryISOAlpha3(),
            'symbole_monnaie' => $this->faker->currencyCode(),
            'continent'       => $this->faker->country(),
            'url_drapeau'     => $this->faker->imageUrl()
        ];
    }
}
