<?php 
namespace App\Services;

use App\Http\Traits\SoldesTrait;
use App\Models\PaiementCommercant;
use App\Models\User;
use Error;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ClientService{
    use SoldesTrait;

    private $payToCommercantTaux = 10;
    private $commercantService;

    public function __construct(CommercantService $commercantService)
    {
        $this->commercantService = $commercantService;
    }


    public function getPaymentData(
        $commercantPhone, 
        $clientUser, 
        $montantARetirer //unité: devise du commercant
    ){
        $commercantUser = $this->commercantService->findCommercantUserByPhone($commercantPhone); 

        $client = $clientUser->client;
        if($client == null) throw new Error("L'utilisateur actuel n'est pas un client");

        //Si montant de différentes devises:
        //TODO: convertir $montantARetirer
        $montantARetirerDeviseClient = $montantARetirer;
            if($commercantUser->pays->id != $clientUser->pays->id) 
                throw new Error("Le pays du client est différent de celui du commercant");
 
        //Verification du solde du client
        $soldeClient = $this->get_solde($clientUser);
        $soldeClient = $soldeClient == null ? 0 : $soldeClient->actuel;
        if($soldeClient < $montantARetirerDeviseClient) throw new Error("Solde insuffisant pour la transaction en cours");

        //Calcul
        $montantCommissions = $montantARetirer*($this->payToCommercantTaux/100);
        $montantACrediter = $montantARetirer - ($montantCommissions);

      
        $details = [];
        $details['nouv_solde'] =  $this->get_solde($clientUser)->actuel - $montantARetirerDeviseClient;
        $details['montant_paye'] = $montantARetirer ;
        $details['montant_paye_devise_cli'] = $montantARetirerDeviseClient ;
        $details['montant_recu_commercant'] =  $montantACrediter;
        $details['montant_commissions'] =  $montantCommissions;
        $details['commercant_user'] = $commercantUser;

        $commercant = [
            "noms"=> $details['commercant_user']->commercant->noms(),
            "telephone"=> $details['commercant_user']->commercant->telephone,
        ];

        $details['commercant'] = $commercant;
        return $details;
    }


    public function getPaymentRecap(
        $commercantPhone, 
        $clientUser, 
        $montantARetirer  ){

        $details = $this->getPaymentData( $commercantPhone, 
            $clientUser, 
            $montantARetirer);
        unset( $details['commercant_user']);
        return $details;
    }
    public function payToCommercant(
        $commercantPhone, 
        $clientUser, 
        $montantARetirer  
        ){
       
        $details = $this->getPaymentData(
            $commercantPhone, 
            $clientUser, 
            $montantARetirer
        );
        //Saving the transaction
        $paiement = PaiementCommercant::create([
            'reference'   => Str::random(10),
            'user_id_from' => $clientUser->id,
            'user_id_to' => $details['commercant_user']->id,
            //TODO: en quel unité?
            'montant'    => $montantARetirer,
            'frais'      => $details['montant_commissions'],
            'taux_from'  => 1,
            'taux_to'    => 1,
            'pays_from'  => $clientUser->client->pays->code,
            'pays_to'    => $clientUser->pays->code,
            'ip_from'    => env('APP_ENV') == 'production' ? request()->ip() : $clientUser->ip_register,
            'ip_to' => $details['commercant_user']->ip_register
        ]);

        

        //Saving the commission 
        $details['commercant_user']->commissions()->create([
            'operation_type' => PaiementCommercant::class,
            'operation_id' => $paiement->id,
            'commission' => $details['montant_commissions']
        ]);

        //Mise à jour solde client
        $this->set_solde($clientUser, $paiement->id, PaiementCommercant::class, $this->new_solde_user_is_from($details['montant_paye_devise_cli'], $clientUser));

        //Mise à jour du solde du commercant
        $this->set_solde($details['commercant_user'], $paiement->id, PaiementCommercant::class, $this->new_solde_user_is_to( $details['commercant_user'], $details['montant_recu_commercant'] ));

        unset( $details['commercant_user']);
        return $details;

    }
}