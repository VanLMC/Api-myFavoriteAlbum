<?php


namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;

class AuthController extends Controller
{



        public function login(Request $request)
        {
            $credentials = request(['email', 'password']);
    
            if (! $token = auth('api')->attempt($credentials)) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }
    
            return $this->respondWithToken($token);
        }
    
    
        protected function respondWithToken($token)
        {
            return response()->json([
                'access_token' => $token,
                'token_type' => 'bearer',
                'expires_in' => auth('api')->factory()->getTTL() * 60
            ]);
        }
    
    
    public function me()
    {
        $user = auth('api')->user();
        $images = $user->images;

        $url_images = [];

        foreach($images as $image){
            
            $url_images[] = url("/storage/images/".$user->id."/".$image->name);
        }
        
        $user->url_images = $url_images;
        $user->makeHidden('images');
        return response()->json($user);
        
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    
}