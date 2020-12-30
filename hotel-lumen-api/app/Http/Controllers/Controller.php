<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;
  
class Controller extends BaseController
{
    public function respondWithToken($token, $userDto)
    {
        return response()->json([
            'token' => $token,
            'token_type' => 'bearer',
            'expires_in' => Auth::factory()->getTTL() * 60,
            'usersDto'=>array(
                'id'=>$userDto->id,
                'name'=>$userDto->name,
                'email'=>$userDto->email,
                'mobile_number'=>$userDto->mobile_number,
                'company_name'=>$userDto->company_name,
                'company_address'=>$userDto->company_address,
            )
        ], 200);
    }
}