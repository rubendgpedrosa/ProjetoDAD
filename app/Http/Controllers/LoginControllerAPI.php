<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Wallet;

define('SERVER_URL', env('SERVER_URL'));
define('CLIENT_ID', '2');
define('CLIENT_SECRET',env('CLIENT_SECRET'));

class LoginControllerAPI extends Controller
{
    public function login(Request $request){
        $email = $request->email;
        $http = new \GuzzleHttp\Client;
        $response = $http->post(SERVER_URL.'/oauth/token', [
            'form_params'=> [
                'grant_type'=> 'password',
                'client_id'=>CLIENT_ID,
                'client_secret'=>CLIENT_SECRET,
                'username'=>$request->email,
                'password'=>$request->password,
                'scope'=>''
            ],
            'exceptions'=> false,
            ]);
        $wallet = Wallet::where('email', $email)->first();
        $user = \App\User::where('email', $email)->first();
        $errorCode = $response->getStatusCode();
        if($errorCode =='200'){
            if($wallet != null){
                return [json_decode((string) $response->getBody(), true), $wallet->id, $user->id];
            }else{
                return [json_decode((string) $response->getBody(), true), null, $user->id];
            }
        }else{
            return response()->json(
            ['msg'=>'User credentials are invalid'], $errorCode);
        }
    }

    public function logout(){
        \Auth::guard('api')->user()->token()->revoke();
        \Auth::guard('api')->user()->token()->delete();
        return response()->json(['msg'=>'Token revoked'], 200);
    }
}
