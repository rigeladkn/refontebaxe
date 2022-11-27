<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class DistributeurFactory extends Factory
{
    /**
    * Define the model's default state.
    *
    * @return array
    */
    public function definition()
    {
        return [
            'user_id'               => User::factory(),
            'pays_id'               => function (array $attributes) { return User::find($attributes['user_id'])->pays_register_id; },
            'nom'                   => $this->faker->firstName(),
            'prenoms'               => $this->faker->lastName(),
            'code_postal'           => random_int(700, 999999),
            'ville'                 => $this->faker->city(),
            'email'                 => function (array $attributes) { return User::find($attributes['user_id'])->email; },
            'telephone'             => function (array $attributes) { return User::find($attributes['user_id'])->telephone; },
            'telephone2'            => $this->faker->phoneNumber(),
            'telephone3'            => $this->faker->phoneNumber(),
            'activite_principale'   => $this->faker->word(),
            'registre_commerce'     => $this->faker->randomNumber(8, true),
            'entreprise_nom'        => $this->faker->company(),
            'path_piece_identitite' => json_encode([
                'recto' => $this->faker->imageUrl(640, 480, 'recto'),
                'verso' => $this->faker->imageUrl(640, 480, 'verso')
            ]),
            'path_media_du_local' => json_encode([
                $this->faker->imageUrl(), $this->faker->imageUrl(), $this->faker->imageUrl()
            ]),
            'communication_baxe' => $this->faker->word()
        ];
    }
}
