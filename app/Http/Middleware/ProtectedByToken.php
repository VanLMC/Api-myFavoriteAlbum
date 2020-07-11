<?php


namespace App\Http\Middleware;

use Closure;
use Tymon\JWTAuth\http\middleware\BaseMiddleware;
use Tymon\JWTAuth\Facades\JWTAuth;
//use JWTAuth;

class ProtectedByToken extends BaseMiddleware
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
            try{
                $user = JWTAuth::parseToken()->authenticate();
            }
                catch (\Exception $e) {
                    if($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException ){
                        return response()->json(['status'=> 'Token expirado']);
                    }
                    else if($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException ){
                        return response()->json(['status'=> 'Token invalido']);
                    }
                    else{
                        return response()->json(['status'=> 'Token nao encontrado']);
                    }
        
                }
            return $next($request);
    }
}
