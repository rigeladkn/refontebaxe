<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Services\ChatService;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    private $chatService; 
    
    public function __construct(ChatService $chatService)
    {
        $this->chatService = $chatService;
    }

    public function token(Request $request)
    {
        $data = [];
        try{
            $token = $this->chatService->getToken($request->identity);
            $data["status"] = "ok";
            $data["data"] =  $token;
        }catch(\Throwable $e){
            $data["status"] = "ko";
            $data["message"] =  $e->getMessage();
        }
        return response()->json($data, 200);
    }

    public function employes()
    {
        $data = [];
        try{
            $employes = $this->chatService->getEmailEmployes();
            $data["status"] = "ok";
            $data["data"] =  $employes;
        }catch(\Throwable $e){
            $data["status"] = "ko";
            $data["message"] =  $e->getMessage();
        }
        return response()->json($data, 200);
    }

    public function clients()
    {
        $data = [];
        try{
            $clients = $this->chatService->getEmailClients();
            $data["status"] = "ok";
            $data["data"] =  $clients;
        }catch(\Throwable $e){
            $data["status"] = "ko";
            $data["message"] =  $e->getMessage();
        }
        return response()->json($data, 200);
    }

    public function createTwilioClient(Request $request)
    {
        $data = [];
        try{
            $this->chatService->createTwilioClient($request->userId);
            $data["status"] = "ok";
        }catch(\Throwable $e){
            $data["status"] = "ko";
            $data["message"] =  $e->getMessage();
        }
        return response()->json($data, 200);
    }
}
