<?php

namespace Database\Seeders;

use App\Models\Client;
use App\Models\User;
use Illuminate\Database\Seeder;

class ClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create();

        foreach (User::all() as $user)
        {
            Client::create([
                'user_id'     => $user->id,
                'pays_id'     => $user->pays_register_id,
                'nom'         => $faker->firstName(),
                'prenoms'     => $faker->lastName(),
                'code_postal' => random_int(700, 999999),
                'ville'       => $faker->city(),
                'email'       => $user->email,
                'telephone'   => $user->telephone,
            ]);
        }

        Client::factory()->count(10)->has(User::factory())->create();
    }
}
