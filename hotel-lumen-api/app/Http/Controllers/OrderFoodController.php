<?php

namespace App\Http\Controllers;

use App\Models\OrderFood;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OrderFoodController extends Controller
{
    protected $request;
    public function __construct(Request $request) {
        $this->request = $request;
    }

    public function getOrdersByTabelId($orderTbleId){
        try {
            $result=OrderFood::where('booked_tabel_id',$orderTbleId)->get();
            if($result){
                return response()->json(['message'=>'Successfully created order food', "data"=>$result],200);
            }else{
                return response()->json(['message'=>'Successfully not created order food'],400);
            }
        }catch (\Exception $th) {
            return response()->json(['message'=>$th->getMessage()],400);
        }
    }

    /**
         * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request){
        try {
            $validator = $this->validateStore();
            if($validator->fails()){
                return response()->json(['message'=>$validator->messages()],400);
            }
            $orderFood= $request->all();
            $result = OrderFood::create($orderFood);
            if($result){
                return response()->json(['message'=>'Successfully created order food', "data"=>$result],200);
            }else{
                return response()->json(['message'=>'Successfully not created order food'],400);
            }
        } catch (\Exception $th) {
            return response()->json(['message'=>$th->getMessage()],400);
        }
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\OrderFood  $orderFood
     * @return \Illuminate\Http\Response
     */
    public function update($orderFoodId,Request $request){
        try {
            $validator = $this->validateStore();
            if($validator->fails()){
                return response()->json(['message'=>$validator->messages(),'data'=>null],400);
            }
            $orderFood= $request->all();
            $result = OrderFood::where('order_food_id',$orderFoodId)->update($orderFood);
            if($result){
                return response()->json(['message'=>'Successfully updated food order by id '.$orderFoodId, "data"=>$orderFood ],200);
            }else{
                return response()->json(['message'=>'Successfully not updated food order by id '.$orderFoodId],400);
            }
        } catch (\Exception $th) {
            return response()->json(['message'=>$th->getMessage()],400);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\OrderFood  $orderFood
     * @return \Illuminate\Http\Response
     */
    public function destroy($orderFoodId){
        try {
            $result = OrderFood::where('order_food_id',$orderFoodId)->delete();
            if($result){
                return response()->json(['message'=>'Successfully deleted order food by id '.$orderFoodId],200);
            }else{
                return response()->json(['message'=>'Successfully not deleted table by id '.$orderFoodId],404);
            }
        } catch (\Exception $th) {
            return response()->json(['message'=>$th->getMessage()],400);
        }
    }

    public function validateStore(){
        return Validator::make(request()->all(), [
            'order_food_name' => 'required',
            'order_food_unit_price' => 'required',
            'order_food_qty' => 'required',
            'order_food_total_price' => 'required',
            'booked_tabel_id' => 'required'
        ]);
    }
}
