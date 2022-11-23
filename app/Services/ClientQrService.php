<?php
namespace App\Services;

use App\Models\BtClientCredential;
use Illuminate\Support\Facades\DB;

class ClientQrService {

    public function findClientCredentials ($decodedText){

        // $credentials = DB::table('bt_client_credentials')
        // ->selectRaw('id, nom, prenoms')
        // ->where('id', '=', intval($decodedText))
        // ->first();

        $credentials = BtClientCredential::find([intval($id)])->first()->select(['nom','prenoms']);
        return $credentials ;
    }

    public function findClientCredentialsById ($id){
        
        // $credentials = DB::table('bt_client_credentials')
        //     ->selectRaw('id, nom, prenoms, id as decoded_text')
        //     ->where('id', '=', intval($id))
        //     ->first();
        $credentials = BtClientCredential::where('id',intval($id))->first();  
        return $credentials->id;
    
    }
}