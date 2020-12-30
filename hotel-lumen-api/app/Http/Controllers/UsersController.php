<?php

namespace App\Http\Controllers;

use Illuminate\Hashing\BcryptHasher;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\Users;

class UsersController extends Controller
{

    public function test(){
       echo "Welcome to project its successfully runing";
    }

    public function getAllUsers(){
        return response()-> json(Users::all());
    }

    public function getUsersById($id){
        return response()-> json(Users::find($id));
    }


    //this function is used to register a new user
    public function create(Request $request)
    {
        //creating a validator
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' =>'required|unique:users',
            'mobile_number'=>'required',
            'company_name'=>'required',
            'company_address'=>'required',
            'adhar_number'=>'required',
            'pan_number'=>'required',
            'password'=>'required'
        ]);
        //if validation fails 
        if ($validator->fails()) {
            return array(
                'error' => true,
                'message' => $validator->errors()->all()
            );
        }
        //creating a new user
        $users = new Users();
        //adding values to the users
        $users->name = $request->input('name');
        $users->email = $request->input('email');
        $users->mobile_number = $request->input('mobile_number');
        $users->company_name = $request->input('company_name');
        $users->company_address = $request->input('company_address');
        $users->adhar_number = $request->input('adhar_number');
        $users->mobile_number = $request->input('mobile_number');
        $users->pan_number = $request->input('pan_number');
        $users->password = (new BcryptHasher)->make($request->input('password'));
        //saving the user to database
        $users->save();
        //unsetting the password so that it will not be returned 
        unset($users->password);
        //returning the registered user 
        return response()->json($users);
    }

    public function updateUser($id,Request $request){
        //creating a validator
        $validator = Validator::make($request->all(), [
            'username' => 'required',
            'password' => 'required',
            'email' => 'required'
        ]);
        //if validation fails 
        if ($validator->fails()) {
            return array(
                'error' => true,
                'message' => $validator->errors()->all()
            );
        }else{
            $exitsUser= Users::findOrFail($id);
            $exitsUser->update($request->all());
            return response()->json($exitsUser);
        }
    }

    public function deleteUser($id){
        $exitsUser= Users::findOrFail($id)->delete();
        return response()->json("'Deleted Successfully'");
    }
}