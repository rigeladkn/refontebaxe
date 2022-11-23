<?php 
namespace App\Services;

use App\Models\Client;
use App\Models\Employe;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Twilio\Jwt\AccessToken;
use Twilio\Jwt\Grants\VideoGrant;
use Twilio\Jwt\Grants\SyncGrant;
use Twilio\Jwt\Grants\ChatGrant;

class ChatService {

    
    public function getToken($identity){
        $token = new AccessToken(
            env('TWILIO_ACCOUNT_SID'),
            env('TWILIO_API_KEY'),
            env('TWILIO_API_SECRET'),
            3600,
            $identity
        );
        $chatGrant = new ChatGrant();
        $chatGrant->setServiceSid(env('TWILIO_CHAT_SERVICE_SID'));
        $token->addGrant($chatGrant);
        return $token->toJWT();
    }

    public function getEmailEmployes(){
        return DB::table('employes')
            ->join('users', 'employes.user_id', '=', 'users.id')
            ->where('users.twilio_client', '=', '1')
            ->select('employes.email')
            ->get();
    }

    public function createTwilioClient($userId){
        $user = User::find($userId);
        $user->twilio_client = true;
        $user->save();
    }

    public function getEmailClients(){
        return DB::table('clients')
            ->join('users', 'clients.user_id', '=', 'users.id')
            ->where('users.twilio_client', '=', '1')
            ->select('clients.email')
            ->get();
    }
}