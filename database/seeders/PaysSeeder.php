<?php

namespace Database\Seeders;

use App\Http\Traits\CountriesTrait;
use App\Models\Pays;
use Illuminate\Database\Seeder;

class PaysSeeder extends Seeder
{
    use CountriesTrait;
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach ($this->countries_list_of_codes($this->countries_all()) as $countrie)
        {
            if (array_key_exists('root', $countrie['idd']) && array_key_exists('currencies', $countrie) && array_key_exists('symbol', $countrie['currencies'][array_key_first($countrie['currencies'])]))
            {
                Pays::create([
                    'indicatif'       => $countrie['idd']['root'].''.$countrie['idd']['suffixes'][0],
                    'nom'             => $countrie['translations']['fra']['common'],
                    'code'            => $countrie['cca2'],
                    'monnaie'         => reset($countrie['currencies'])['symbol'],
                    'symbole_monnaie' => array_key_first($countrie['currencies']),
                    'continent'       => $countrie['continents'][0],
                    'url_drapeau'     => $countrie['flags']['png']
                ]);
            }
        }

        /* Pays::create([
            'indicatif' => '+225',
            'nom' => 'Côte d\'Ivoire',
            'code' => 'CI'
        ]);

        Pays::create([
            'indicatif' => '+33',
            'nom' => 'France',
            'code' => 'FR'
        ]);

        Pays::create([
            'indicatif' => '+241',
            'nom' => 'Gabon',
            'code' => 'GA'
        ]);

        Pays::create([
            'indicatif' => '+233',
            'nom' => 'Ghana',
            'code' => 'GH'
        ]);

        Pays::create([
            'indicatif' => '+212',
            'nom' => 'Maroc',
            'code' => 'MA'
        ]);

        Pays::create([
            'indicatif' => '+221',
            'nom' => 'Sénégal',
            'code' => 'SN'
        ]);

        Pays::create([
            'indicatif' => '+216',
            'nom' => 'Tunisie',
            'code' => 'TN'
        ]);

        Pays::create([
            'indicatif' => '+1',
            'nom' => 'États-Unis',
            'code' => 'US'
        ]); */
    }
}
