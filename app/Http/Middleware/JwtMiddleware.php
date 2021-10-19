<?php

namespace App\Http\Middleware;

use Closure;
use JWTAuth;
use Exception;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;

class JwtMiddleware
{
    public function handle($request, Closure $next)
    {

      $token = str_replace('Bearer ', "" , $request->header('Authorization'));
      try {
        JWTAuth::setToken($token); //<-- set token and check
          if (! $claim = JWTAuth::getPayload()) {
            return response()->json([
                'status' => 'failure',
                'response' =>  [
                  'code' => '200',
                  'type' => 'user_not_found',
                  'message' =>  "Session expired, Please login again to continue."
                ],
              ], 200);
          }
      } catch (\Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
          return response()->json([
              'status' => 'failure',
              'response' =>  [
                'code' => '200',
                'type' => 'token_expired',
                'message' =>  "Token Expired, Please login again to continue"
              ],
            ], 200);
      } catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
        return response()->json([
            'status' => 'failure',
            'response' =>  [
              'code' => '200',
              'type' => 'token_invalid',
              'message' =>  "Token Invalid, Please login again to continue"
            ],
          ], 200);
      } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
        return response()->json([
            'status' => 'failure',
            'response' =>  [
              'code' => '200',
              'type' => 'token_absent',
              'message' =>  "Token Absent, Please login again to continue"
            ],
          ], 200);
      }
      $cookies = JWTAuth::getPayload()->get('sub');
      $cookies = (array) $cookies;
      if(isset($cookies['email'])){
        $request->merge(['email' => $cookies['email']]);
      }
      if(isset($cookies['name'])){
        $request->merge(['name' => $cookies['name']]);
      }
      if(isset($cookies['token'])){
        $request->merge(['token' => $cookies['token']]);
      }
      return $next($request);
    }
}
