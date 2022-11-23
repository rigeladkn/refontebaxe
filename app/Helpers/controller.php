<?php

use App\Mail\MailSender;
use App\Models\Solde;
use Illuminate\Support\Collection;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Mail;

if (!function_exists('storage_file'))
{
    function storage_file($request, $path, $input_file, $name = null)
    {
        $storage_path = null;

        if($request->hasfile($input_file))
        {
            if (is_array($request->file($input_file)))
            {
                Storage::deleteDirectory('public/'.$path);

                foreach($request->file($input_file) as $file)
                {
                    if ($file->isValid())
                    {
                        if ($name)
                        {
                            $file_name = $file->storeAs($path, $name.'.'.$request->$input_file->extension(), 'public');
                            $storage_path[] = $file_name;
                        }
                        else
                        {
                            $file_name = $file->store($path, 'public');
                            $storage_path[] = $file_name;
                        }
                    }
                    else
                    {
                        $storage_path[] = '';
                    }
                }
            }
            else
            {
                if ($request->file($input_file)->isValid())
                {
                    if ($name)
                    {
                        $file_name = $request->file($input_file)->storeAs($path, $name.'.'.$request->$input_file->extension(), 'public');
                        $storage_path = $file_name;
                    }
                    else
                    {
                        $file_name = $request->file($input_file)->store($path, 'public');
                        $storage_path = $file_name;
                    }
                }
                else
                {
                    $storage_path = '';
                }
            }
        }

        return $storage_path;
    }
}

if (!function_exists('get_geolocation'))
{
    function get_geolocation ($ip_source = null)
    {
        $apiKey = 'b601e700ee0549e3b441ec4c8bab72f9';

        // Obtenir son IP : https://www.mon-ip.com/

        if (env('APP_ENV') == 'production' && !$ip_source)
        {
            $ip = request()->ip();
        }
        elseif ($ip_source)
        {
            $ip = $ip_source;
        }
        // Test
        else
        {
            // $ip = '160.154.151.210'; // CI
            $ip = '93.177.75.198'; // France
        }

        $lang = "fr";

        $fields = "*";

        $excludes = "";

        try
        {
            $response = Http::get("https://api.ipgeolocation.io/ipgeo?apiKey=$apiKey&ip=$ip&lang=$lang&fields=$fields&excludes=$excludes");

            return $response->json();
        }
        catch (\Throwable $th)
        {
            dd($th);
        }
    }
}

if (!function_exists('create_pagination_with_collection'))
{
    function create_pagination_with_collection($collection, $par_page, $page = null, $options = [])
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $collection = $collection instanceof Collection ? $collection : Collection::make($collection);
        return new LengthAwarePaginator($collection->forPage($page, $par_page), $collection->count(), $par_page, $page, $options);
    }
}

if (!function_exists('getUserSolde'))
{
    function getUserSolde($user)
    {
        try {
            $solde = Solde::where([
                "user_id" => $user->id
            ])->first()->actuel;
        } catch (\Throwable $th) {
            $solde = 0;
        }
        // dd($solde);
        return $solde;
    }
}

if (! function_exists('send_sms'))
{
    function send_sms($telephone, $message)
    {
        $sid = "AC0eb1f3ee29c7daca888c18ee0cd0958e"; //"AC3d2d2372864d1443bfe49913f631ea69"; // Your Account SID from www.twilio.com/console
        $token = "a173587e2b4cbd2640bde0690737a4b5"; //"8892bb69e27ad4b729a28f5454e27dcd"; // Your Auth Token from www.twilio.com/console
        
        $client = new Twilio\Rest\Client($sid, $token);
        $message = $client->messages->create(
            $telephone, // Text this number
            [
                'from' => 'Baxe', // From a valid Twilio number
                'body' => $message
            ]
        );
        return ($message->sid) ? true : false;
        
        //return true;
    }
}


if (! function_exists('send_mail'))
{
    function send_mail($email, $title, $message)
    {
        $details = [
            "subject" => config("app.name"),
            "title" => $title,
            "body" => $message
        ];
    
        Mail::to($email)->send(new MailSender($details));
        return true;
    }
}


if (! function_exists('send_code'))
{
    function send_code($type, $to, $code, $for)
    {
        $title = ($for === "inscription") ? ("Confirmation de compte ") : ("Vérification de code ");

        $message = $code." est votre code de vérification d'identité";

        if($type === "mail") {
            $message = "Veuillez valider votre compte en cliquant sur le lien : \n\n".
            "<a class='btn btn-primary' href='https://baxe-moneytransfer.com/api/validation/code?email=$to&emailCode=$code'>Valider mon compte</a>";
            return send_mail($to, $title, $message);
        } else {
            return send_sms($to, $message);
        }
    }
}

if(! function_exists('generateRandomNumber')) {
    function generateRandomNumber($length = 6) {
        $characters = '0123456789';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}
