<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class productController extends Controller
{
     // LISTAR PRODUCTOS 
     public function index(){

        $productos = Product::all();

        // if($productos->empty()){
        //     $data = [
        //         "message" => "No se encontraron productos",
        //         "status" => 200
        //     ];
        //     return response()->json($data,404);
        // }

        $data = [
            "productos" => $productos,
            "status" => 200
        ];

        return response()->json($data,200);
    
    }

    
    // --------------------------------------------------------------------------------------
    public function store(Request $request) {
        try {
            // Definimos las reglas de validación
            $validator = Validator::make($request->all(), [
                "name" => "required",
                "price" => "required|numeric", // Ejemplo: asegura que price sea numérico
                "category" => "required",
                "code" => "required",
                "description" => "required",
                "stock_quantity" => "required|integer", // Ejemplo: asegura que stock_quantity sea entero
                "status" => "required",
            ]);
    
            // Validamos si la validación falla
            if ($validator->fails()) {
                return response()->json([
                    "message" => "Error en la validación de los datos",
                    "errors" => $validator->errors(),
                    "status" => 400
                ], 400);
            }
    
            // Creamos el producto
            $producto = Product::create([
                "name" => $request->name,
                "price" => $request->price,
                "category" => $request->category,
                "code" => $request->code,
                "description" => $request->description,
                "stock_quantity" => $request->stock_quantity,
                "status" => $request->status,
            ]);
    
            // Si la creación fue exitosa
            if ($producto) {
                return response()->json([
                    "producto" => $producto,
                    "status" => 201
                ], 201);
            } else {
                return response()->json([
                    "message" => "Error al crear el producto",
                    "status" => 500
                ], 500);
            }
    
        } catch (\Exception $e) {
            // Capturamos y manejamos cualquier excepción no esperada
            return response()->json([
                "message" => "Error interno del servidor",
                "exception_message" => $e->getMessage(),
                "status" => 500
            ], 500);
        }
    }
    


    // --------------------------------------------------------------------------
    // buscar por producto detallado por su id

    public function show($id){

        $producto = Product::find($id);

        // si no se encuentra un producto
        if(!$producto){
            $data = [
                "message" => "Producto no encontrado",
                "status" => 404
            ];
            return response()->json($data,404);
        }

        // si la busqueda fue exitosa
        $data = [
            "message" => $producto,
            "status" =>200
        ];

        return response()->json($data,200);
        
    }



    // ------------------------------------------------------------
    // eliminar producto
    public function delete($id){

        $producto = Product::find($id);

        // si no encuentra el producto
        if(!$producto){

            $data = [
                "message" => "Producto no encontrado",
                "status" => 404
            ];
    
            return response()->json($data,404);
        }


        // si la busqueda fue exitosa
        $producto->delete();

        $data = [
            "message" => "Producto eliminado exitosamente",
            "status" => 204
        ];

        return response($data,204);
        
    }

    
    // ---------------------------------------------------------------------------------
    // editar todo el producto
    public function update(Request $request, $id)
    {
        // Buscamos el producto por su ID
        $producto = Product::find($id);
    
        // Si el producto no existe, retornamos un mensaje de error
        if (!$producto) {
            $data = [
                "message" => "Producto no encontrado",
                "status" => 404
            ];
            return response()->json($data, 404);
        }
    
        // Validamos los datos recibidos en la solicitud
        $validator = Validator::make($request->all(), [
            "name" => "required",
            "price" => "required",
            "category" => "required",
            "code" => "required",
            "description" => "required",
            "stock_quantity" => "required",
            "status" => "required",
        ]);
    
        // Si la validación falla, retornamos los errores al cliente
        if ($validator->fails()) {
            $data = [
                "message" => "Error en la validación de los datos",
                "errors" => $validator->errors(),
                "status" => 400
            ];
            return response()->json($data, 400);
        }
    
        // Actualizamos los campos del producto con los nuevos valores
        $producto->name = $request->input('name');
        $producto->price = $request->input('price');
        $producto->category = $request->input('category');
        $producto->code = $request->input('code');
        $producto->description = $request->input('description');
        $producto->stock_quantity = $request->input('stock_quantity');
        $producto->status = $request->input('status');

        // Guardamos los cambios en la base de datos
        $producto->save();
    
        // Preparamos la respuesta de éxito
        $data = [
            "message" => "Producto actualizado exitosamente",
            "producto" => $producto,
            "status" => 200
        ];
    
        // Retornamos la respuesta al cliente
        return response()->json($data, 200);
    }


    // --------------------------------------------------------------------------------
    // actualizar parcial el producto
    public function updatePartial(Request $request, $id)
    {
        // Buscamos el producto por su ID
        $producto = Product::find($id);
    
        // Si el producto no existe, retornamos un mensaje de error
        if (!$producto) {
            $data = [
                "message" => "Producto no encontrado",
                "status" => 404
            ];
            return response()->json($data, 404);
        }
    
        // Validamos los datos recibidos en la solicitud
        $validator = Validator::make($request->all(), [
            "name" => "max:200",
            "price" => "max:200",
            "category" => "max:200",
            "code" => "max:200",
            "description" => "max:200",
            "stock_quantity" => "max:200",
            "status" => "max:200",
        ]);
    
        // Si la validación falla, retornamos los errores al cliente
        if ($validator->fails()) {
            $data = [
                "message" => "Error en la validación de los datos",
                "errors" => $validator->errors(),
                "status" => 400
            ];
            return response()->json($data, 400);
        }
    
        // Actualizamos los campos del producto solo si existen en la solicitud
        if ($request->has("name")) {
            $producto->name = $request->input('name');
        }
    
        if ($request->has("price")) {
            $producto->price = $request->input('price');
        }
    
        if ($request->has("category")) {
            $producto->category = $request->input('category');
        }
    
        if ($request->has("code")) {
            $producto->code = $request->input('code');
        }

        if ($request->has("description")) {
            $producto->description = $request->input('description');
        }
    
        if ($request->has("stock_quantity")) {
            $producto->stock_quantity = $request->input('stock_quantity');
        }
    
        if ($request->has("status")) {
            $producto->status = $request->input('status');
        }
    
    
        // Guardamos los cambios en la base de datos
        $producto->save();
    
        // Preparamos la respuesta de éxito
        $data = [
            "message" => "Producto actualizado exitosamente",
            "producto" => $producto,
            "status" => 200
        ];
    
        // Retornamos la respuesta al cliente
        return response()->json($data, 200);
    }
}
