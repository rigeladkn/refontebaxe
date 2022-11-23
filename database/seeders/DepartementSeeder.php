<?php

namespace Database\Seeders;

use App\Models\Departement;
use Illuminate\Database\Seeder;

class DepartementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Departement::create([
            'nom' => 'Admin',
        ]);

        Departement::create([
            'nom' => 'Commercial',
        ]);

        Departement::create([
            'nom' => 'Gestionnaire de compte',
        ]);

        Departement::create([
            'nom' => "Comptabilité"
        ]);
    }
}
