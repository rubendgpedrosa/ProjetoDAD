<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Contracts\Support\Jsonable;

use App\Http\Resources\User as UserResource;
use Illuminate\Support\Facades\DB;

use App\User;
use App\Http\Controllers\MovementsControllerAPI as Movement;
use App\Http\Controllers\WalletControllerAPI as Wallet;
use App\StoreUserRequest;
use Hash;
use Illuminate\Support\Facades\Storage;

class UserControllerAPI extends Controller
{
    public function index(Request $request)
    {
        $users = User::all();
        foreach($users as $user){
            $wallet = \App\Wallet::where('email', $user->email)->first();
            if($wallet != null){
                if($wallet->balance > 0){
                    $user->empty_wallet = false;
                }else{
                    $user->empty_wallet = true;
                }
            }
        }
        return $users;
        /*if ($request->has('page')) {
            return UserResource::collection(User::paginate(5));
        } else {
            return UserResource::collection(User::all());
        }*/
    }

    public function show($id)
    {
        return new UserResource(User::find($id));
    }

    public function uploadImage(Request $request, $id){
        $exploded = explode(',', $request->photo);
        $decoded = base64_decode($exploded[1]);
        if(strpos($exploded[0], 'jpeg') !== false){
            $extension = '.jpg';
        }
        else{
            $extension = '.png';
        }
        $string = md5($id);
        $partial_string = $string.substr(0, 12);
        $fileName = $id .'_'.$partial_string.$extension;
        $path = public_path().'/storage/fotos/'.$fileName;
        file_put_contents($path, $decoded);
        return $fileName;
    }

    public function store(Request $request)
    {
        /*As an Administrator of the platform I want to create operator and administration
          accounts. These accounts will only have a name (only spaces and letters), a photo (upload
          a JPG file), a password (3 or more characters) and an e-mail, which must be unique
          among all accounts of the platform (including the platform users).*/
        $walletController = new WalletControllerAPI();
        $userController = new UserControllerAPI();
        $request->validate([
                'name' => 'required|min:3|regex:/^[A-Za-záàâãéèêíóôõúçÁÀÂÃÉÈÍÓÔÕÚÇ ]+$/',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|min:3',
                'nif' => 'integer',
            ]);
        $user = new User();
        $user->fill($request->except('photo'));
        $user->password = Hash::make($user->password);
        $request->type == null? $user->type = 'u':$user->type = $request->type;
        $user->save();
        if($user->type == 'u'){
            $walletController->store($request);
        }
        //\Log::info($request->all());
        if($request->photo != null){
            $user->photo = $userController->uploadImage($request, $user->id);
        }else{
            $user->photo = 'default.png';
        }
        return response()->json($user->save());
    }

    public function update(Request $request)
    {
        $userController = new UserControllerAPI();
        $request->validate([
            'name' => 'min:3|regex:/^[A-Za-záàâãéèêíóôõúçÁÀÂÃÉÈÍÓÔÕÚÇ ]+$/',
            'password' => 'integer|min:3',
            'nif' => 'integer',
        ]);
        $user = User::findOrFail($request->id);
        $wallet = \App\Wallet::where('email', $request->email)->first();
        if($request->new_password != null) {
            if(Hash::check($request->password, $user->password)){
                $user['password'] = Hash::make($request->new_password);
            }
            else{
               abort(403);
            }
        }
        if($request->name != null && $request->name != $user->name){
            $user->name = $request->name;
        }
        if($request->nif != null && $request->nif != $user->nif){
            $user->nif = $request->nif;
        }
        if($request->photo != null && $request->photo != $user->photo){
            $user->photo = $userController->uploadImage($request, $user->id);
        }
        if($request->type_update != null && $request->type_update == "activity"){
            if($wallet->balance > 0){
                return abort(403);
            }else{
                if($user->active != 1){
                    $user->active = 1;
                    $user->save();
                }else{
                    $user->active = 0;
                    $user->save();
                }
            }
        }
        return response()->json($user->save());
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        if($user->type == 'u'){
            return abort(403);
        }{
            $wallet = new Wallet();
            $movements = new Movement();
            $wallet = $wallet->show($id);
            if($wallet != null){
                $wallet->delete();
            }
            $user->delete();
            return response()->json(null, 204);
        }
    }

    public function emailAvailable(Request $request)
    {
        $totalEmail = 1;
        if ($request->has('email') && $request->has('id')) {
            $totalEmail = DB::table('users')->where('email', '=', $request->email)->where('id', '<>', $request->id)->count();
        } else if ($request->has('email')) {
            $totalEmail = DB::table('users')->where('email', '=', $request->email)->count();
        }
        return response()->json($totalEmail == 0);
    }
}
