<?php

namespace Database\Seeders;

use App\Models\Depot;
use App\Models\Retrait;
use Illuminate\Database\Seeder;
use App\Models\CommissionModalite;

class CommissionModaliteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /* COMMISSION_DEPOT_DISTRIBUTEUR */
        /* AFRIQUE */
        CommissionModalite::create([
            'operation'         => Depot::class,
            'continent'         => 'Africa',
            'from'              => 1,
            'to'                => 999999,
            'frais_pourcentage' => 0.28,
            'frais_fixe'        => 0
        ]);

        CommissionModalite::create([
            'operation'         => Depot::class,
            'continent'         => 'Africa',
            'from'              => 1000000,
            'to'                => 1499999,
            'frais_pourcentage' => 0.38,
            'frais_fixe'        => 0
        ]);

        CommissionModalite::create([
            'operation'         => Depot::class,
            'continent'         => 'Africa',
            'from'              => 1500000,
            'to'                => 1999999,
            'frais_pourcentage' => 0,
            'frais_fixe'        => 6300
        ]);

        CommissionModalite::create([
            'operation'         => Depot::class,
            'continent'         => 'Africa',
            'from'              => 2000000,
            'to'                => 2999999,
            'frais_pourcentage' => 0,
            'frais_fixe'        => 7300
        ]);

        CommissionModalite::create([
            'operation'         => Depot::class,
            'continent'         => 'Africa',
            'from'              => 3000000,
            'to'                => 3999999,
            'frais_pourcentage' => 0,
            'frais_fixe'        => 8300
        ]);

        CommissionModalite::create([
            'operation'         => Depot::class,
            'continent'         => 'Africa',
            'from'              => 4000000,
            'to'                => 10000000000,
            'frais_pourcentage' => 0.42,
            'frais_fixe'        => 0
        ]);
        /* AFRIQUE */

        /* EUROPE */
        CommissionModalite::create([
            'operation'         => Depot::class,
            'continent'         => 'Europe',
            'from'              => 1,
            'to'                => 10000000000,
            'frais_pourcentage' => 0.26,
            'frais_fixe'        => 0
        ]);
        /* EUROPE */

        /* AMERIQUE */
        CommissionModalite::create([
            'operation'         => Depot::class,
            'continent'         => 'North America, South America',
            'from'              => 1,
            'to'                => 10000000000,
            'frais_pourcentage' => 0.28,
            'frais_fixe'        => 0
        ]);
        /* AMERIQUE */

        /* ASIE */
        CommissionModalite::create([
            'operation'         => Depot::class,
            'continent'         => 'Asia',
            'from'              => 1,
            'to'                => 10000000000,
            'frais_pourcentage' => 0.28,
            'frais_fixe'        => 0
        ]);
        /* ASIE */
        /* COMMISSION_DEPOT_DISTRIBUTEUR */

        /* COMMISSION_RETRAIT_DISTRIBUTEUR */
        /* AFRIQUE */
        CommissionModalite::create([
            'operation'         => Retrait::class,
            'continent'         => 'Africa',
            'from'              => 1,
            'to'                => 999999,
            'frais_pourcentage' => 0.28,
            'frais_fixe'        => 0
        ]);

        CommissionModalite::create([
            'operation'         => Retrait::class,
            'continent'         => 'Africa',
            'from'              => 1000000,
            'to'                => 1499999,
            'frais_pourcentage' => 0.38,
            'frais_fixe'        => 0
        ]);

        CommissionModalite::create([
            'operation'         => Retrait::class,
            'continent'         => 'Africa',
            'from'              => 1500000,
            'to'                => 1999999,
            'frais_pourcentage' => 0,
            'frais_fixe'        => 6300
        ]);

        CommissionModalite::create([
            'operation'         => Retrait::class,
            'continent'         => 'Africa',
            'from'              => 2000000,
            'to'                => 2999999,
            'frais_pourcentage' => 0,
            'frais_fixe'        => 7300
        ]);

        CommissionModalite::create([
            'operation'         => Retrait::class,
            'continent'         => 'Africa',
            'from'              => 3000000,
            'to'                => 3999999,
            'frais_pourcentage' => 0,
            'frais_fixe'        => 8300
        ]);

        CommissionModalite::create([
            'operation'         => Retrait::class,
            'continent'         => 'Africa',
            'from'              => 4000000,
            'to'                => 10000000000,
            'frais_pourcentage' => 0.42,
            'frais_fixe'        => 0
        ]);
        /* AFRIQUE */

        /* EUROPE */
        CommissionModalite::create([
            'operation'         => Retrait::class,
            'continent'         => 'Europe',
            'from'              => 1,
            'to'                => 10000000000,
            'frais_pourcentage' => 0.26,
            'frais_fixe'        => 0
        ]);
        /* EUROPE */

        /* AMERIQUE */
        CommissionModalite::create([
            'operation'         => Retrait::class,
            'continent'         => 'North America, South America',
            'from'              => 1,
            'to'                => 10000000000,
            'frais_pourcentage' => 0.28,
            'frais_fixe'        => 0
        ]);
        /* AMERIQUE */

        /* ASIE */
        CommissionModalite::create([
            'operation'         => Retrait::class,
            'continent'         => 'Asia',
            'from'              => 1,
            'to'                => 10000000000,
            'frais_pourcentage' => 0.28,
            'frais_fixe'        => 0
        ]);
        /* ASIE */
        /* COMMISSION_RETRAIT_DISTRIBUTEUR */
    }
}
