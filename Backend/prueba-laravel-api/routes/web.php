<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


$router->post("/auth/login",[
    "uses" => "AuthController@authenticate"
]);

$router->group(
    ["middleware" => "jwt.auth"],
    function () use ($router){
        
    }
);