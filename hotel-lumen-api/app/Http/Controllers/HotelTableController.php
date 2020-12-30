<?php

namespace App\Http\Controllers;

use App\Models\HotelTable;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class HotelTableController extends Controller
{
    protected $request;

    public function __construct(Request $request) {
        $this->request = $request;
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
            $user = $this->request->user();
            $request->request->add(['user_id' => $user->id]);
            $request->request->add(['table_booked' => 0]);
            $hotelTable=$request->all();
            $result=HotelTable::create($hotelTable);
            if($result){
                return response()->json(['message'=>'Successfully created hotel table', "data"=>$result ],200);
            }else{
                return response()->json(['message'=>'Successfully not created hotel table'],400);
            }
        } catch (\Exception $th) {
            return response()->json(['message'=>$th->getMessage()],400);
        }
    }

    public function show(Request $request){
        try {
            $user = $this->request->user();
            $stores = HotelTable::where('user_id',$user->id)->get();
            return response()->json(['message'=>'Success','data'=>$stores],200);
        } catch (\Exception $th) {
            return response()->json(['message'=>$th->getMessage()],400);
        }
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\HotelTable  $hotelTable
     * @return \Illuminate\Http\Response
     */
    public function update($tableId,Request $request){
        try {
            $validator = $this->validateStore();
            if($validator->fails()){
                return response()->json(['message'=>$validator->messages(),'data'=>null],400);
            }
            $updatedHotelTable= $request->all();
            $result = HotelTable::where('table_id',$tableId)->update($updatedHotelTable);
            if($result){
                return response()->json(['message'=>'Successfully updated table by id '.$result, "data"=>$updatedHotelTable ],200);
            }else{
                return response()->json(['message'=>'Successfully not updated table by id '.$result],404);
            }
        } catch (\Exception $th) {
            return response()->json(['message'=>$th->getMessage()],400);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\HotelTable  $hotelTable
     * @return \Illuminate\Http\Response
     */
    public function destroy($tableId){
        try {
            $result = HotelTable::where('table_id',$tableId)->delete();
            if($result){
                return response()->json(['message'=>'Successfully deleted table by id '.$result],200);
            }else{
                return response()->json(['message'=>'Successfully not deleted table by id '.$result],404);
            }
        } catch (\Exception $th) {
            return response()->json(['message'=>$th->getMessage()],400);
        }
    }

    public function validateStore(){
        return Validator::make(request()->all(), [
            'table_name' => 'required',
            'table_customer_size' => 'required',
            'table_direction' => 'required'
        ]);
    }
}
