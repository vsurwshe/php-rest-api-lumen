<?php

namespace App\Http\Controllers;

use App\Models\HotelTable;
use App\Models\FreeTabel;
use Illuminate\Http\Request;

class FreeTabelController extends Controller
{
    protected $request;
    public function __construct(Request $request) {
        $this->request = $request;
    }

    public function show()
    {
        try {
            $user = $this->request->user();
            $freeTabels = HotelTable::where('user_id',$user->id)->where('table_booked',0)->get();
            return response()->json(['message'=>'Success','data'=>$freeTabels],200);
        } catch (\Exception $th) {
            return response()->json(['message'=>$th->getMessage()],400);
        }
    }
}
