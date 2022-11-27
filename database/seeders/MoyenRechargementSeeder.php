<?php

namespace Database\Seeders;

use App\Models\MoyenRechargement;
use Illuminate\Database\Seeder;

class MoyenRechargementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        MoyenRechargement::create([
            'nom'        => "Par ma carte",
            'slug'       => 'carte',
            'image_path' => null,
            'icon'       => 'fas fa-qrcode text-dark',
        ]);

        MoyenRechargement::create([
            'nom'        => "Par carte de crédit",
            'slug'       => 'carte-de-credit',
            'image_path' => null,
            'icon'       => "far fa-credit-card text-primary",
        ]);

        MoyenRechargement::create([
            'nom'        => "Par ma banque",
            'slug'       => 'banque',
            'image_path' => null,
            'icon'       => "fas fa-university text-secondary",
            'deleted_at'  => now(),
        ]);
    }
}
