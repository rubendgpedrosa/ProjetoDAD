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
        $http = new \GuzzleHttp\Client;
        $request->validate([
            'password_login' => 'required|min:3',
            'email_login' => 'required|email',
        ]);
        $response = $http->post(SERVER_URL.'/oauth/token', [
            'form_params'=> [
                'grant_type'=> 'password',
                'client_id'=>CLIENT_ID,
                'client_secret'=>CLIENT_SECRET,
                'username'=>$request->email_login,
                'password'=>$request->password_login,
                'scope'=>''
            ],
            'exceptions'=> false,
            ]);
        $errorCode = $response->getStatusCode();
        if($errorCode =='200'){
            return json_decode((string) $response->getBody(), true);
        }else{
            return response()->json(
            ['msg'=>'User credentials are invalid'], 422);
        }
    }

    public function logout(){
        \Auth::guard('api')->user()->token()->revoke();
        \Auth::guard('api')->user()->token()->delete();
        return response()->json(['msg'=>'Token revoked'], 200);
    }
}
