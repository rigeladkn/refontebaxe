<?php 
namespace App\Services;

use App\Models\ClientSession;
use App\Models\Distributeur;

class DistributeurService {

    public function  findDistributeurs ($args) {
        $recordsCount = isset($args->records_count)?$args->records_count : 20;
        $columnToSelect = [ 
            "id"
            , "reference"
            , "user_id"
            , "pays_id"
            , "nom"
            , "prenoms"
            , "code_postal"
            , "ville"
            , "email"
            , "telephone"
            , "telephone2"
            , "telephone3"
            , "entreprise_nom"
            , "created_at"
            , "updated_at"
            , "lng"
            , "lat"
        ];
        if(isset($args->pays_id) && $args->pays_id != ''){
            $distributeurs = Distributeur::select($columnToSelect)->where("pays_id", "=", $args->pays_id )
                ->paginate( $recordsCount);
        }else{
            $distributeurs = Distributeur::select($columnToSelect)->paginate($recordsCount);
        }

        return $distributeurs;
    }

    public function saveClientSession ($clientId, $distributeurUserId){
        $cs = new ClientSession();
        $cs->id_client = $clientId;
        $cs->id_user = $distributeurUserId;
        $cs->save();
    }

    public function findCurrentClientSession ($distributeurUserId){
        $maxCreationDate = ClientSession::where('id_user', '=', intval($distributeurUserId))
            ->whereNull('closed_at')
            ->max('created_at') ;
            //dd($maxCreationDate );
        if($maxCreationDate == null) return null;
        $cs = ClientSession::where('id_user', '=', intval($distributeurUserId))
            ->where("created_at",'=',$maxCreationDate)
            ->first(); 
        return $cs;
    }

}