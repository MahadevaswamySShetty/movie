<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\User;
use Config;
use GuzzleHttp\Client;
use Carbon\Carbon;

class RestController extends Controller
{

    public function __construct()
    {
        $this->middleware('jwt_auth');
    }

    public function movie_list(Request $request)
    {
      $result = [];
      $url  = 'https://api.themoviedb.org/3/movie/550';
      $client = new Client();
      $response = $client->request('GET', $url, [
        'headers' => [
          'Accept'  => 'application/json',
        ],
        'http_errors' => true,
        'query' => [
          'api_key' => 'f3225f0db5ea9f23bafc69ca71382ad9'
        ]
      ]);
      $code = $response->getStatusCode();
      if($code == 200){
        $result = json_decode($response->getBody()->getContents(),TRUE);
      }
      return response()->json([
        'status' => true,
        'message' => 'Movie List!',
        'data' => $result
      ]);
    }

}
