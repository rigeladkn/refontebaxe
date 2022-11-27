<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
 

class WelcomeController extends Controller
{
    public function index(){
        return view('index');
    }
    public function contact(){
        return view('contact');
    }

    public function about(){
        return view('about');
    } 
    public function signup(){
        return view('signup');
    } 
    public function login(){
        return view('login');
    }
}