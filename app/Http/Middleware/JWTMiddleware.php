<?php

namespace App\Http\Middleware;

use App\Helpers\ResponseHelper;
use Closure;
use JWTAuth;
use Exception;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;
use Illuminate\Http\Request;

class JWTMiddleware
{
    public $response;
    public function __construct(){
        $this->response = new ResponseHelper();
    }
    public function handle(Request $request, Closure $next, ...$roles)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();

        } catch(Exception $e){
            if($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException){
                return $this->response->errorResponse('Token Invalid');
            }
            else if($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException){
                return $this->response->errorResponse('Token Expired');
            } else {
                return $this->response->errorResponse('Authorization token not found');
            }
        }

        //If user was authenticated successfully and user is in one of the acceptable roles, send to next request.
        if ($user && in_array($user->level, $roles)) {
            return $next($request);
        }

        return $this->response->errorResponse('You are unauthorize user');
    }
}
