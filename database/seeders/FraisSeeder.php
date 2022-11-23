<?php

namespace Database\Seeders;

use App\Models\Depot;
use App\Models\Frais;
use App\Models\Transfert;
use Illuminate\Database\Seeder;

class FraisSeeder extends Seeder
{
    /**
    * Run the database seeds.
    *
    * @return void
    */
    public function run()
    {
        /* FRAIS_TRANSFERT */
        /* CI */
        Frais::create([
            'operation'         => Transfert::class,
            'continent'         => 'Africa',
            'from'              => 'Africa',
            'to'                => 'national',
            'frais_pourcentage' => 1,
            'frais_fixe'        => 0
        ]);

        Frais::create([
            'operation'         => Transfert::class,
            'continent'         => 'Africa',
            'from'              => 'Africa',
            'to'                => 'international',
            'frais_pourcentage' => 3,
            'frais_fixe'        => 0
        ]);
        /* CI */

        /* GB */
        Frais::create([
            'operation'         => Transfert::class,
            'continent'         => 'Africa',
            'from'              => 'XAF',
            'to'                => 'national',
            'frais_pourcentage' => 1.5,
            'frais_fixe'        => 0
        ]);

        Frais::create([
            'operation'         => Transfert::class,
            'continent'         => 'Africa',
            'from'              => 'XAF',
            'to'                => 'international',
            'frais_pourcentage' => 3,
            'frais_fixe'        => 0
        ]);
        /* GB */

        /* FR */
        Frais::create([
            'operation'         => Transfert::class,
            'continent'         => 'Europe',
            'from'              => 'FR',
            'to'                => 'national',
            'frais_pourcentage' => 0,
            'frais_fixe'        => 0
        ]);

        Frais::create([
            'operation'         => Transfert::class,
            'continent'         => 'Europe',
            'from'              => 'FR',
            'to'                => 'international',
            'frais_pourcentage' => 1,
            'frais_fixe'        => 0
        ]);
        /* FR */

        /* Europe */
        Frais::create([
            'operation'         => Transfert::class,
            'continent'         => 'Europe',
            'from'              => 'Europe',
            'to'                => 'national',
            'frais_fixe'        => 1,
            'frais_pourcentage' => 0,
        ]);

        Frais::create([
            'operation'         => Transfert::class,
            'continent'         => 'Europe',
            'from'              => 'Europe',
            'to'                => 'international',
            'frais_pourcentage' => 1,
            'frais_fixe'        => 0,
        ]);
        /* Europe */

        /* Amerique */
        Frais::create([
            'operation'         => Transfert::class,
            'continent'         => 'North America, South America',
            'from'              => 'North America, South America',
            'to'                => 'national',
            'frais_fixe'        => 1,
            'frais_pourcentage' => 0,
        ]);

        Frais::create([
            'operation'         => Transfert::class,
            'continent'         => 'North America, South America',
            'from'              => 'North America, South America',
            'to'                => 'international',
            'frais_pourcentage' => 1,
            'frais_fixe'        => 0,
        ]);
        /* Amerique */

        /* Asie */
        Frais::create([
            'operation'         => Transfert::class,
            'continent'         => 'Asia',
            'from'              => 'Asia',
            'to'                => 'national',
            'frais_fixe'        => 1,
            'frais_pourcentage' => 0,
        ]);

        Frais::create([
            'operation'         => Transfert::class,
            'continent'         => 'Asia',
            'from'              => 'Asia',
            'to'                => 'international',
            'frais_pourcentage' => 1,
            'frais_fixe'        => 0,
        ]);
        /* Asie */
        /* FRAIS_TRANSFERT */
    }
}
