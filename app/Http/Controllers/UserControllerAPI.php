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

        if ($request->has('page')) {
            return UserResource::collection(User::paginate(5));
        } else {
            return UserResource::collection(User::all());
        }
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
        $walletController = new WalletControllerAPI();
        $userController = new UserControllerAPI();
        $request->validate([
                'name' => 'required|min:3|regex:/^[A-Za-záàâãéèêíóôõúçÁÀÂÃÉÈÍÓÔÕÚÇ ]+$/',
                'email' => 'required|email|unique:users,email',
                'age' => 'integer|between:18,75',
                'password' => 'min:3'
            ]);
        $user = new User();
        $user->fill($request->except('photo'));
        $user->password = Hash::make($user->password);
        $user->save();
        if($user->type == "u"){
            $walletController->store($request);
        }
        //\Log::info($request->all());
        $user->photo = $userController->uploadImage($request, $user->id);
        $user->save();
        return $user;
    }

    public function update(Request $request, $id)
    {
        $request->validate([
                'name' => 'required|min:3|regex:/^[A-Za-záàâãéèêíóôõúçÁÀÂÃÉÈÍÓÔÕÚÇ ]+$/',
                'email' => 'required|email|unique:users,email,'.$id,
                'age' => 'integer|between:18,75'
            ]);
        $user = User::findOrFail($id);
        $user->update($request->all());
        return new UserResource($user);
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $wallet = new Wallet();
        $movements = new Movement();
        $wallet = $wallet->show($id);
        $wallet->delete();
        $user->delete();
        return response()->json(null, 204);
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
