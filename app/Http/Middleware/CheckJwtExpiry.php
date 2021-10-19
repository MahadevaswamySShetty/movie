<?php

namespace App\Http\Middleware;

use Closure;
use JWTAuth;
use Exception;
use Cookie;
use Carbon\Carbon;
use JWTFactory;
use Cache;
use Route;
use Config;
use GuzzleHttp\Client;

class CheckJwtExpiry
{

    public function handle($request, Closure $next)
    {
      if(empty($request->cookie('jwt_token'))){
        return redirect(route('logout'));
      }
      $token = $request->cookie('jwt_token');
      $token_type = "success";
      try {
        JWTAuth::setToken($token);
        if (!$claim = JWTAuth::getPayload()) {
           $token_type = "logout";
        } else {
          if(Carbon::now()->format("Y-m-d H:i:s") > Carbon::parse(date("Y-m-d H:i:s",JWTAuth::getPayload()->get('exp')))->subMinutes(15)->format("Y-m-d H:i:s")){
            $token_type = "refresh";
          }
        }
      } catch (\Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
        $token_type = "expired";
      } catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
        $token_type = "logout";
      } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
         $token_type = "logout";
      } catch (Tymon\JWTAuth\Exceptions\TokenBlacklistedException $e) {
         $token_type = "logout";
      }
     finally {
        if($token_type == 'logout'){
          return redirect()->route('logout');
        } else if ($token_type == 'refresh') {
          return redirect()->route('refresh_token',['redirect_url' => $request->fullUrl()]);
        } else if ($token_type == 'expired') {
          return redirect()->route('refresh_token',['redirect_url' => $request->fullUrl()]);
        } else {
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
          $request->headers->set('Authorization', 'Bearer ' . $token);
          return $next($request);
        }
      }


  }

  private function respondWithError()
  {
    return response()->json(['my_custom_key' => 'value']);
  }

}
