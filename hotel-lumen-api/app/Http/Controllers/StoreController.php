<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Store;
use App\Models\User;

class StoreController extends Controller
{

    protected $request;

    public function __construct(Request $request) {
        $this->request = $request;
    }

    public function getAllStoreElements()
    {
        try {
            $user = $this->request->user();
            $stores = Store::where('user_id',$user->id)->get();
            return response()->json(['message'=>'Success','data'=>$stores],200);
        } catch (\Exception $th) {
            return response()->json(['message'=>$th->getMessage()],400);
        }
    }

    public function saveStoreElementRecord(Request $request){
        try {
            $validator = $this->validateStore();
            if($validator->fails()){
                return response()->json(['message'=>$validator->messages(),'data'=>null],400);
            }
            $user = $this->request->user();
            $request->request->add(['user_id' => $user->id]);
            $store=$request->all();
            $result=Store::create($store);
            if($result){
                return response()->json(['message'=>'Successfully created store product', "data"=>$result ],200);
            }else{
                return response()->json(['message'=>'Successfully not created store product'],400);
            }
        } catch (\Exception $th) {
            return response()->json(['message'=>$th->getMessage()],400);
        }
    }
    
    public function updateStoreElementRecord($productId, Request $request){
        try {
            $validator = $this->validateStore();
            if($validator->fails()){
                return response()->json(['message'=>$validator->messages(),'data'=>null],400);
            }
            $updatedStore= $request->all();
            $stores = Store::where('store_id',$productId)->update($updatedStore);
            if($stores){
                return response()->json(['message'=>'Successfully updated store element by store '.$productId, "data"=>$stores ],200);
            }else{
                return response()->json(['message'=>'Successfully not updated store element by store '.$productId],404);
            }
        } catch (\Exception $th) {
            return response()->json(['message'=>$th->getMessage()],400);
        }
    }

    public function deleteStoreElementRecord($productId, Request $request){
        try {
            $stores = Store::where('store_id',$productId)->delete();
            if($stores){
                return response()->json(['message'=>'Successfully deleted store element by store id '.$productId],200);
            }else{
                return response()->json(['message'=>'Successfully not deleted store element by store id '.$productId],404);
            }
        } catch (\Exception $th) {
            return response()->json(['message'=>$th->getMessage()],400);
        }
    }

    public function validateStore(){
        return Validator::make(request()->all(), [
            'store_product_name' => 'required',
            'store_product_qty' => 'required',
            'store_product_total_price' => 'required'
        ]);
    }
}
