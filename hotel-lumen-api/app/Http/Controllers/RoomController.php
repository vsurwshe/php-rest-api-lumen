<?php

namespace App\Http\Controllers;

use App\Models\Customers;
use App\Models\Rooms;
use App\Models\RoomBooking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RoomController extends Controller
{
    public $request;
    public function __construct(Request $request) {
        $this->request = $request;
    }

    // this method will help to get list of booked room
    public function getListOfBookedRoom(){
        try {
            $user = $this->request->user();
            $result = RoomBooking::where('user_id',$user->id)->get();
            return response()->json(['message'=>'Success','data'=>$result],200);
        } catch (\Exception $th) {
            return response()->json(['message'=>$th->getMessage()],400);
        }
    }

    // this method will help to get list of free room
    public function getListOfFreeRoom(){
        try {
            $user = $this->request->user();
            $result = Rooms::where('user_id',$user->id)->where('room_booking_status',0)->get();
            return response()->json(['message'=>'Success','data'=>$result],200);
        } catch (\Exception $th) {
            return response()->json(['message'=>$th->getMessage()],400);
        }
    }

    // this method will help to get list of today checkout rooms
    public function getListOfTodayCheckOutRoom(){
        try {
            $user = $this->request->user();
            $result = RoomBooking::where('user_id',$user->id)->where('check_out_date',date("Y/m/d"))->get();
            return response()->json(['message'=>'Success','data'=>$result],200);
        } catch (\Exception $th) {
            return response()->json(['message'=>$th->getMessage()],400);
        }
    }

    // this method will help to get total count of customer
    public function getTotalCountOfCustomer(){
        try {
            $user = $this->request->user();
            $result = Customers::where('user_id',$user->id)->get();
            return response()->json(['message'=>'Success','data'=>$result],200);
        } catch (\Exception $th) {
            return response()->json(['message'=>$th->getMessage()],400);
        }
    }

    // this method will help to get total list of rooms
    public function getListOfRoom(){
        try {
            $user = $this->request->user();
            $result = Rooms::where('user_id',$user->id)->get();
            return response()->json(['message'=>'Success','data'=>$result],200);
        } catch (\Exception $th) {
            return response()->json(['message'=>$th->getMessage()],400);
        }
    }

    // this function will help to return details of room booking with customer and room
    public function getRoomBookingDetails($roomBookingId){
        try {
            $user = $this->request->user();
            $fetchRoomBookingData = RoomBooking::where('room_booking_id',$roomBookingId)->get();
            if(count($fetchRoomBookingData)){
                $resultCustomer= Customers::where('customer_id',$fetchRoomBookingData[0]['customer_id'])->get();
                $resultRoomBooking= RoomBooking::where('room_booking_id',$roomBookingId)->get();
                if(count($resultCustomer) >0 && count($resultRoomBooking)>0 ){
                    return response()->json(['message'=>"Successfully fetched room booking details by id $roomBookingId","data"=>array_merge(json_decode($resultCustomer[0],true),json_decode($resultRoomBooking[0],true))],200);
                }else{
                    throw new \Exception("There is no record for customer or room booking  by id $roomBookingId roombooking id");    
                }
            }else{
                throw new \Exception("There is no record for this id $roomBookingId");
            }
        } catch (\Exception $th) {
            return response()->json(['message'=>$th->getMessage()],400);
        }
    }

    // this method will help to saving room booking details
    public function saveBookRoomDetails(Request $request){
        try {
            $validator = $this->validateRoomBooking();
            if($validator->fails()){
                return response()->json(['message'=>$validator->messages()],400);
            }
            $user = $this->request->user();
            $customerDTO=$request->input('customer_dto');
            $roomBookingDTO=$request->input('room_booking_dto');
            $customerId=$this->saveCustomer($user,$customerDTO);
            if( substr($roomBookingDTO[0]['check_in_date'], 0, 10) === date('Y-m-d')){
                $roomBooking= $this->roomBookingDetails($roomBookingDTO,$customerId,$user);
                $result=$this->updateBookingStatusOfRoom($roomBooking);
                return response()->json(['message'=>"Successfully booked room", 'data'=>$roomBooking],200);
            }else{
                $roomBooking= $this->roomBookingDetails($roomBookingDTO,$customerId,$user);
                return response()->json(['message'=>"Successfully booked room for date ".$roomBooking->check_in_date, 'data'=>$roomBooking],200);
            }
        } catch (\Exception $th) {
            return response()->json(['message'=>$th->getMessage()],400);
        }
    }

    // this method will help to update room booking details
    public function updateBookRoomDetails($roomBookingId,Request $request){
        try {
            $validator = $this->validateRoomBooking();
            if($validator->fails()){
                return response()->json(['message'=>$validator->messages()],400);
            }
            $user = $this->request->user();
            $fetchRoomBookingData = RoomBooking::where('room_booking_id',$roomBookingId)->get();
            $customerDTO=$request->input('customer_dto');
            $roomBookingDTO=$request->input('room_booking_dto');
            if(count($fetchRoomBookingData)){
                $updateResultCustomer= Customers::where('customer_id',$fetchRoomBookingData[0]['customer_id'])->update($customerDTO[0]);
                $updateResultRoomBooking= RoomBooking::where('room_booking_id',$roomBookingId)->update($roomBookingDTO[0]);
                if($updateResultCustomer >0 && $updateResultRoomBooking>0 ){
                    return response()->json(['message'=>"Successfully udpated room booking details $roomBookingId"],200);
                }else{
                    throw new \Exception("Successfully not updated room booking details by id $roomBookingId");    
                }
            }else{
                throw new \Exception("There is no record for this id $roomBookingId");
            }
        } catch (\Exception $th) {
            return response()->json(['message'=>$th->getMessage()],400);
        }
    }

    // this method will help to update room booking details
    public function deleteBookRoomDetails($roomBookingId){
        try {
            $fetchResult= RoomBooking::where('room_booking_id',$roomBookingId)->get();
            if(count($fetchResult)>0){
                $updateResult = Rooms::where('room_id',$fetchResult[0]['room_id'])->update(array('room_booking_status'=>0));
                $result= RoomBooking::where('room_booking_id',$roomBookingId)->delete();
                if($result){
                    return response()->json(['message'=>'Successfully room booking by id '.$roomBookingId],200);
                }else{
                   throw new \Exception("Successfully not deleted table by id $roomBookingId");
                }
            }else{
                return response()->json(['message'=>'there is not record found by id '.$roomBookingId],404);
            }
        } catch (\Exception $th) {
            return response()->json(['message'=>$th->getMessage()],400);
        }
    }

    // -------------------------------Room COntroller section-----
    
    // this method will help to get room details by id
    public function getRoomDetails($roomId){
        try {
            $user = $this->request->user();
            $result = Rooms::where('user_id',$user->id)->where('room_id',$roomId)->get();
            return response()->json(['message'=>'Success','data'=>$result],200);
        } catch (\Exception $th) {
            return response()->json(['message'=>$th->getMessage()],400);
        }
    }

    // this method will help to save room details 
    public function saveRoomDetails(Request $request){
        try {
            $validator = $this->validateRoomDetails();
            if($validator->fails()){
                return response()->json(['message'=>$validator->messages()],400);
            }
            $user = $this->request->user();
            $request->request->add(['user_id' => $user->id]);
            $request->request->add(['room_booking_status' => 0]);
            $roomDetails= $request->all();
            $result = Rooms::create($roomDetails);
            if($result){
                return response()->json(['message'=>'Successfully created room record', "data"=>$result],200);
            }else{
                return response()->json(['message'=>'Successfully not created room record'],400);
            }
        } catch (\Exception $th) {
            return response()->json(['message'=>$th->getMessage()],400);
        }
    }

    // this method will help to update room details
    public function updateRoomDetails($roomId,Request $request){
        try {
            $validator = $this->validateRoomDetails();
            if($validator->fails()){
                return response()->json(['message'=>$validator->messages(),'data'=>null],400);
            }
            $roomDetails= $request->all();
            $result = Rooms::where('room_id',$roomId)->update($roomDetails);
            if($result){
                return response()->json(['message'=>'Successfully updated room details by id '.$roomId, "data"=>$result ],200);
            }else{
                return response()->json(['message'=>'Successfully not updated room details by id '.$roomId],400);
            }
        } catch (\Exception $th) {
            return response()->json(['message'=>$th->getMessage()],400);
        }
    }

    // this method will help to update room details
    public function deleteRoomDetails($roomId){
        try {
            $result = Rooms::where('room_id',$roomId)->delete();
            if($result){
                return response()->json(['message'=>'Successfully deleted room details by id '.$roomId],200);
            }else{
                return response()->json(['message'=>'Successfully not deleted room details by id '.$roomId],404);
            }
        } catch (\Exception $th) {
            return response()->json(['message'=>$th->getMessage()],400);
        }
    }

    //------------------ Helping method section
    // this method will help to update booking status of room
     public function updateBookingStatusOfRoom($roomBooking){
        $roomBookingStatus = Rooms::where('room_id',$roomBooking->room_id)->get();
        if($roomBookingStatus[0]["room_booking_status"]>0){
            throw new \Exception("Your Room is already booked for other record");
        }else{
            $result = Rooms::where('room_id',$roomBooking->room_id)->update(array('room_booking_status'=>1));
            if($result){
                return $result;
            }else{
                throw new \Exception("Your Room is not booked successfully");
            }
        }
    }

    // this method will help to save room booking details
    public function roomBookingDetails($roomBookingDTO,$customerId,$user){
        $result= RoomBooking::where('check_in_date',$roomBookingDTO[0]['check_in_date'])
                ->where('check_out_date',$roomBookingDTO[0]['check_out_date'])
                ->where('room_id',$roomBookingDTO[0]['room_id'])
                ->get();
        if(count($result)<=0){
            $roomBooking= new RoomBooking();
            $roomBooking->check_in_date= $roomBookingDTO[0]['check_in_date'];
            $roomBooking->check_out_date= $roomBookingDTO[0]['check_out_date'];
            $roomBooking->room_booking_customer_size= $roomBookingDTO[0]['room_booking_customer_size'];
            $roomBooking->room_booking_gst= $roomBookingDTO[0]['room_booking_gst'];
            $roomBooking->room_id=$roomBookingDTO[0]['room_id'];
            $roomBooking->customer_id=$customerId;
            $roomBooking->room_booking_total=0;
            $roomBooking->room_booking_subtotal=0;
            $roomBooking->user_id=$user->id;
            $roomBooking->save();
            if($roomBooking){
                return $roomBooking;
            }else{
                throw new \Exception("Your Room is not booked successfully");
            }
        }else{
            throw new \Exception("Your room is already booked for another record");
        }
    }

    // this method help to save customer details while booking room
    public function saveCustomer($user,$customerDTO){
        $customerId="";
        if($this->checkUniqueUser($customerDTO[0]["customer_card_number"],$user)){
            $customerDTO[0]['user_id']= $user->id;
            $result= Customers::create($customerDTO[0]);
            return $result->id;
        }else{
            $result = Customers::where('user_id',$user->id)->where('customer_card_number',$customerDTO[0]["customer_card_number"])->get()->toArray();
            return $result[0]["customer_id"];
        }
    }

    // this method help to check user unique or not while saving user deatils
    public function checkUniqueUser($cardNumber, $user){
        $result = Customers::where('user_id',$user->id)->where('customer_card_number',$cardNumber)->get();
        return count($result) != 0 ? false : true;
    }

    public function validateRoomDetails(){
        return Validator::make(request()->all(), [
            'room_number' => 'required',
            'room_locations' => 'required',
            'room_type' => 'required',
            'room_rate' => 'required'
        ]);
    }

    public function validateRoomBooking(){
        return Validator::make(request()->all(), [
            'customer_dto'   => 'required|array|min:1',
            'room_booking_dto'   => 'required|array|min:1',
            'customer_dto.*.customer_name' => 'required',
            'customer_dto.*.customer_card_number' => 'required',
            'customer_dto.*.customer_card_type' => 'required',
            'customer_dto.*.customer_email' => 'required',
            'customer_dto.*.customer_mobile_number' => 'required',
            'customer_dto.*.customer_address' => 'required',
            'room_booking_dto.*.check_in_date' => 'required',
            'room_booking_dto.*.check_out_date' => 'required',
            'room_booking_dto.*.room_booking_customer_size' => 'required',
            'room_booking_dto.*.room_booking_gst' => 'required',
            'room_booking_dto.*.room_id' => 'required'
        ]);
    }
}
