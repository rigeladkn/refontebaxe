<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\Pays;
use App\Models\DemandeDistributeur;


class DemandeDistributeurSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for($i=0; $i<20; $i++){
            DemandeDistributeur::create([
                'pays_register_id'    => Pays::where('indicatif', "+33")->first()->id,
                'ip_register'         => '188.165.59.127',
                'recent_ip'           => '188.165.59.127',
                'nom'                 => 'nom-user-'.$i,
                'prenoms'             => 'prenom-user'.$i,
                'code_postal'         => rand(),
                'ville'               =>  'ville-user-'.$i,
                'email'               =>  'utilisateu-'.$i.'@gmail.com',
                'telephone'           =>  rand().$i,
                'telephone2'          =>  rand().$i,
                'telephone3'          =>  rand().$i,
                'activite_principale' => 'activite-user'.$i,
                'registre_commerce'   =>  'XD-'.Str::random(5).'-20'.$i,
                'entreprise_nom'      => "Entreprise-user".$i,
                'num_compte_bancaire' => rand().$i,
                'path_piece_identite' => json_encode('piece_identite'.$i),
                'path_media_du_local' => json_encode('Locale'),
                'communication_baxe'  => 'Facebook',
            ]);
        }
    }
}
