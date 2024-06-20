<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Firebase\JWT\JWT;
use Laravel\Lumen\Routing\Controller as BaseController;

class AuthController extends BaseController
{
    private $request;
    
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function jwt(User $user)
    {
        $payload = [
            "iss" => "api-youtube-jwt",
            "sub" => $user->id,  // Cambiado de "sup" a "sub"
            "iat" => time(),
            "exp" => time() + 60 * 60
        ];
        return JWT::encode($payload, env("JWT_SECRET"), "HS256");
    }

    public function authenticate()
    {
        $this->validate($this->request, [
            "email" => "required|email",  // Cambiado de "requerided" a "required"
            "password" => "required"      // Cambiado de "requerided" a "required"
        ]);

        $user = User::where("email", $this->request->input("email"))->first();

        if (!$user) {
            return response()->json([
                "error" => "El email no existe"
            ], 400);
        }

        if ($this->request->input("password") == $user->password) {
            return response()->json([
                "token" => $this->jwt($user)
            ], 200);
        }

        return response()->json([
            "error" => "El correo o la contraseña son incorrectos"
        ], 400);
    }
}


// namespace App\Http\Controllers;

// use Illuminate\Http\Request;
// use app\Models\User;
// use Firebase\JWT\JWT;
// use Laravel\lumen\Routing\Controller as BaseController;

// class AuthController extends Controller
// {
//     private $request;
    
//     public function __construct(Request $request){
//         $this->request = $request;
//     }

//     public function jwt(User $user){

//         $payload = [
//             "iss" => "api-youtube-jwt",
//             "sup" => $user->id,
//             "iat" => time(),
//             "exp" => time() + 60 * 60
//         ];
//         return JWT::encode($payload,env("JWT_SECRET"),"HS256");
//     }

//     public function authenticate(User $user){
//         $this->validate($this->request,[
//             "email" => "requerided|email",
//             "password" =>"requerided"
//         ]);

//         $user = User::where("email",$this->request->input("email"))->first();

//         if(!$user){
//             return response()->json([
//                 "error" => "el email no existe"
//             ],400);
//         }
//         if($this->request->input("password") == $user->password){
//             return response()->json([
//                 "token" => $this->jwt($user)
//             ],200);
//         }

//         return response()->json([
//             "error" => "el correo o el password estan incorrectos"
//         ],400);
//     }
// }
