<?php

namespace Database\Seeders;

use App\Models\Solde;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Gate;

class SoldeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (User::all() as $user)
        {
            if (Gate::forUser($user)->allows('is-distributeur'))
            {
                Solde::create([
                    'user_id' => $user->id,
                    'ancien'  => random_int(1000, 1000000),
                    'actuel'  => random_int(1000, 1000000),
                ]);
            }
        }

        // Solde::factory(10)->has(User::factory())->create();
    }
}
