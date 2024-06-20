<?php

use App\Http\Controllers\Api\CompraController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\productController;


// listar productos
Route::get("/products",[productController::class,"index"]);

//listar un producto en especifico
Route::get("/products/{id}",[productController::class,"show"]);

//crear producto
Route::post("/products",[productController::class,"store"]);

//editar producto
Route::put("/products/{id}",[productController::class,"update"]);

//editar producto especifico
Route::patch("/products/{id}",[productController::class,"updatePartial"]);

//eliminar producto
Route::delete("/products/{id}",[productController::class,"delete"]);

// realizar compra
// Route::post('/compras', [compraController::class,"crearCompra"]);

$router->post("/auth/login",[
    "uses" => "AuthController@authenticate"
]);

$router->group(
    ["middleware" => "jwt.auth"],
    function () use ($router){

    }
);
