<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\User;
use Cookie;
use JWTAuth;
use JWTFactory;
use Carbon\Carbon;
use GuzzleHttp\Client;

class HomeController extends Controller
{
  public function __construct()
  {
    $this->middleware('jwt_expiry', ['except' => ['login','register','signin','signup','logout','refresh_token']]);
  }

  public function login(Request $request)
  {
      if (Cookie::has('jwt_token')) {
        return redirect()->intended(route('home'));
      }
      return view('auth.login');
  }

  public function register(Request $request)
  {
      return view('auth.register');
  }

  public function signin(Request $request)
  {
    $request->validate([
        'email'   => 'required',
        'password'=> 'required',
    ]);
    $credentials = request(['email', 'password']);
    if (! $token = auth('api')->attempt($credentials)) {
        return response()->json([
          'status' => false,
          'message' => 'Unauthorized!'
        ]);
    }
      $user = [
         'email' => auth('api')->user()->email,
         'name' => auth('api')->user()->name,
      ];
      $factory = JWTFactory::customClaims([
           'sub'   => $user,
      ]);
      $payload = $factory->make();
      $token = JWTAuth::encode($payload);
      $token =  "{$token}";
      JWTAuth::setToken($token);
      Cookie::queue(Cookie::forget('jwt_token'));
      Cookie::queue('jwt_token',$token,20160);
      return response()->json([
        'status' => true,
        'message' => 'User Signin successfully!'
      ]);

  }

  public function signup(Request $request)
  {
    $request->validate([
        'name'    => 'required',
        'email'   => 'required',
        'password'=> 'required|confirmed',
    ]);
    $data = User::where('email',$request->get('email'))->first();
    if (!empty($data)) {
      return response()->json([
          'status' => false,
          'message' => 'User Aleady Exists!',
      ]);
    }else {
      User::create([
          'name'     => request('name'),
          'email'    => request('email'),
          'password' => Hash::make(request('password')),
      ]);
      return response()->json([
        'status' => true,
        'message' => 'User SignUp successfully!'
      ]);
    }

  }

  public function logout(Request $request)
  {
    if (Cookie::has('jwt_token')) {
      try {
        JWTAuth::setToken(Cookie::get('jwt_token'));
        $cookies = JWTAuth::getPayload()->get('sub');
        $cookies = (array) $cookies;
        JWTAuth::invalidate();
      } catch (\Exception $e) {

      }
      Cookie::queue(Cookie::forget('jwt_token'));
      return redirect()->route('login');
    }
    return redirect()->route('login');
  }

  public function refresh_token(Request $request)
  {
      $token_type = 'failure';
      if(!empty($request->cookie('jwt_token'))){
        try {
          $token = $request->cookie('jwt_token');
          JWTAuth::setToken($token);
          $token = JWTAuth::refresh($token);
          Cookie::queue(Cookie::forget($token));
          Cookie::queue('jwt_token',$token,JWTAuth::factory()->getTTL() * 60);
          $token_type = 'success';
        } catch (\Exception $e) {

        }
      }
      if($token_type == 'success'){
        if($request->input('redirect_url')){
          return redirect($request->input('redirect_url'));
        } else {
          return redirect()->route('home');
        }
      }
      return redirect()->route('logout');
  }

  public function home()
  {
      return view('home');
  }


}
