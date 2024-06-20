<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use App\Models\User;
use Firebase\JWT\JWT;
use Firebase\JWT\ExpiredException;
use Illuminate\Http\Request;

class JwtMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // Verificar si existe el encabezado Authorization
        if (!$request->header("Authorization")) {
            return response()->json([
                "error" => "Se requiere el token"
            ], 401);
        }

        // Obtener el token JWT del encabezado Authorization
        $token = $request->header("Authorization");

        // Separar el token de la palabra "Bearer"
        $token = str_replace("Bearer ", "", $token);

        try {
            // Decodificar el token JWT
            $credentials = JWT::decode($token, env("JWT_SECRET"), ["HS256"]);
        } catch (ExpiredException $e) {
            return response()->json([
                "error" => "El token ha expirado"
            ], 400);
        } catch (Exception $e) {
            return response()->json([
                "error" => "Algo ha ocurrido al decodificar el token"
            ], 400);
        }

        // Obtener el usuario basado en el sub (subject) del token
        $user = User::find($credentials->sub);

        if (!$user) {
            return response()->json([
                "error" => "Usuario no encontrado"
            ], 404);
        }

        // Adjuntar el usuario autenticado al objeto Request para su uso posterior
        $request->auth = $user;

        return $next($request);
    }
}


// namespace App\Http\Middleware;

// use Closure;
// use Exception;
// use App\Models\User;
// use Firebase\JWT\JWT;
// use Firebase\JWT\Key;
// use Firebase\JWT\ExpiredException;
// use Illuminate\Http\Request;
// use PhpParser\Node\Stmt\TryCatch;
// use Symfony\Component\HttpFoundation\Response;

// class JwtMiddleware
// {
//     /**
//      * Handle an incoming request.
//      *
//      * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
//      */
//     public function handle(Request $request, Closure $next): Response
//     {
//         if($request->header("Authorization")){
//             return response()->json([
//                 "error" => "Se requiere el token"
//             ],401);
//         }

//         $array_token = explode(" ",$request->header("Authorization"));
//         $token = $array_token[1];

//         try{
//             $credentials = JWT::decode($token,new Key(env("JWT_SECRET"),"HS256"));
//         }catch(ExpiredException $e){
//             return response()->json([
//                 "error" => "El token ha expirado"
//             ],400);
//         }catch(Exception $e){
//             return response()->json([
//                 "error" => "Algo ha ocurrido al decodear el token"
//             ],400);
//         }

//         $user = User::find($credentials->sub);

//         $request->auth = $user;

//         return $next($request);
//     }
// }
