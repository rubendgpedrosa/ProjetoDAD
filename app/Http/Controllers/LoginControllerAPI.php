<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

define('SERVER_URL', 'http://projetodad.test/');
define('CLIENT_ID', '2');
define('CLIENT_SECRET','U2RvWE6nAYqujHnBJWBxaBaVZIe2dWdzmjdX28sM');

class LoginControllerAPI extends Controller
{
    public function login(Request $request){
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

            $errorCode = $response->getStatusCode();
            if($errorCode=='200'){
                return json_decode((string) $response->getBody(), true);
            }else{
                return response()->json(
                ['msg'=>'User credentials are invalid'], $errorCode);
            }
    }
}
