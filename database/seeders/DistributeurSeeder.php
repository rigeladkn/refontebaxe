<?php

namespace Database\Seeders;

use App\Models\Distributeur;
use App\Models\User;
use Illuminate\Database\Seeder;

class DistributeurSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Distributeur::factory()->count(10)->has(User::factory())->create();
    }
}
