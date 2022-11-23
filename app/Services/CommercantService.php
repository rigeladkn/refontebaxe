<?php 
namespace App\Services;

use App\Models\User;
use Error;

class CommercantService{

    public function findCommercantUserByPhone($commercantPhone){
        $commercantUser = User::where ('telephone', $commercantPhone)->first();
        if($commercantUser == null) throw new Error("Telephone de commercant inconnu");
        $commercant = $commercantUser->commercant;
        if($commercant == null) throw new Error("Telephone de commercant inconnu");
        return $commercantUser;
    }
}