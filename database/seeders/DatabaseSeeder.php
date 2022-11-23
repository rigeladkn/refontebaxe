<?php

namespace Database\Seeders;

use App\Models\Pays;
use App\Models\User;
use App\Models\Departement;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Database\Seeders\DepartementSeeder;

class DatabaseSeeder extends Seeder
{
    /**
    * Seed the application's database.
    *
    * @return void
    */
    public function run()
    {
        if (env('APP_ENV') == 'local')
        {
            $this->call([
                PaysSeeder::class,
                FraisSeeder::class,
                CommissionModaliteSeeder::class,
                DepartementSeeder::class,
                UserSeeder::class,
                ClientSeeder::class,
                DistributeurSeeder::class,
                SoldeSeeder::class,
                MoyenRechargementSeeder::class,
                DemandeDistributeurSeeder::class,
            ]);
        }

        if (env('APP_ENV') == 'production')
        {
            $this->call([
                PaysSeeder::class,
                FraisSeeder::class,
                CommissionModaliteSeeder::class,
                MoyenRechargementSeeder::class,
                DepartementSeeder::class,
            ]);
        }

        $admin = User::create([
            'pays_register_id'  => Pays::where('code', 'FR')->first()->id,
            'ip_register'       => '127.0.0.1',
            'email'             => 'admin@baxe-moneytransfer.com',
            'telephone'         => '+33123456789',
            'email_verified_at' => now(),
            'recent_ip'         => '127.0.0.1',
            'password'          => Hash::make('Ypx^m652G?T#'),
        ]);

        $employe = $admin->employe()->create([
            'user_id' => $admin->id,
            'pays_id' => $admin->pays_register_id,
            'nom'     => 'Admin',
            'prenoms' => 'Baxe',
            'email'   => 'admin@baxe-moneytransfer.com'
        ]);

        $employe->departements()->attach([
            'departement_id' => Departement::where('nom', 'Admin')->first()->id,
        ]);
    }
}
