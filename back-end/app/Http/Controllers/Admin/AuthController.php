<?php
use App\Models\User;

namespace App\Http\Controllers\Admin;
use Illuminat\Support\Facades;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function loginAdmin(Request $request){
        $credentials = $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

     if (Auth::attempt($credentials)) {
        $user = Auth::user();
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Bienvenue de nouveau !',
            'user' =>$user,
            'token' => $token

        ], 200); 
    }
     return response()->json([
        'message' => 'Les identifiants fournis sont incorrects.',
    ], 400);  


    }


    public function getUser()
    {
             if (Auth::check()) {
             return response()->json(Auth::user());
        }    
        return response()->json(['message' => 'User not authenticated'], 401);
        // return response()->json(Auth::user()->only(['id', 'name', 'email']));
       
        // return response()->json([
        //     'name' => Auth::user()->name,
        //     'email' => Auth::user()->email,
        // ]);   
    }


    public function logout(Request $request)
{
    $request->user()->tokens()->delete();

    return response()->json([
        'message' => 'Déconnexion réussie.',
    ], 200);
}

}
