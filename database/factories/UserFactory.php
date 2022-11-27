<?php

namespace Database\Factories;

use App\Models\Pays;
use Illuminate\Support\Str;
use App\Http\Traits\CountriesTrait;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserFactory extends Factory
{
    /**
    * Define the model's default state.
    *
    * @return array
    */
    public function definition()
    {
        $ip = $this->faker->ipv4();

        return [
            'pays_register_id'  => config('app.env') == 'testing' ? Pays::factory() : random_int(1, Pays::all()->count()),
            'ip_register'       => $ip,
            'email'             => $this->faker->unique()->safeEmail(),
            'email_verified_at' => now(),
            'telephone'         => $this->faker->phoneNumber(),
            'recent_ip'         => $ip,
            'password'          => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',   // password
            'remember_token'    => Str::random(10),
        ];
    }

    /**
    * Indicate that the model's email address should be unverified.
    *
    * @return \Illuminate\Database\Eloquent\Factories\Factory
    */
    public function unverified()
    {
        return $this->state(function (array $attributes) {
            return [
                'email_verified_at' => null,
            ];
        });
    }
}
