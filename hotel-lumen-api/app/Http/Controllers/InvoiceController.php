<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\User;
use App\Models\InvoiceItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\BookedTabelController;

class InvoiceController extends Controller
{
    protected $request;
    public $bookedTabelController;
    public function __construct(Request $request) {
        $this->request = $request;
        $this->bookedTabelController= new BookedTabelController($request);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function saveInvoiceElementRecord(Request $request){
        try {
            $validator = $this->validateStore();
            if($validator->fails()){
                return response()->json(['message'=>$validator->messages()],400);
            }
            $invoiceItemList=$request->input('invoice_items');
            // this condtion checks whether we provide invoice items or not
            if(sizeof($invoiceItemList) > 0){
                $user = $this->request->user();
                $invoice = new Invoice();
                $subtotal= $this->calculateSubTotal($invoiceItemList);
                $invoicedGst = (($subtotal*18)/100);
                $totalInvoicePrice=$subtotal+$invoicedGst;
                $invoice->invoice_table=$request->input('invoice_table');
                $invoice->invoice_sub_total=$subtotal;
                $invoice->invoice_gst=$invoicedGst;
                $invoice->invoice_total_price=$totalInvoicePrice;
                $invoice->user_id=$user->id;
                // this line saving main pro body data
                $invoice->save();
                foreach($invoiceItemList as $item){
                    $this->saveInvoiceItem($item,$invoice->id);
                }
                if($invoice){
                    $result = $this->bookedTabelController->destroy($request->input('invoice_table'));
                    return response()->json(['message'=>'Successfully saved invoice','data'=>$invoice],200);
                }else{
                    return response()->json(['message'=>'Successfully not saved invoice','data'=>$invoice],400);
                }
            }
        } catch (\Exception $th) {
            return response()->json(['message'=>$th->getMessage()],400);
        }
    }

    public function calculateSubTotal($itemList){
        $subtotal=0;
        if(sizeof($itemList)>0){
            foreach($itemList as $item){
                $subtotal+=$item["invoice_item_total_price"];
            }
        }
        return $subtotal;
    }

    public function saveInvoiceItem($invoiceItem, $invoiceId){
        $data[]=[
            "invoice_item_name"=>$invoiceItem["invoice_item_name"],
            "invoice_item_price"=>$invoiceItem["invoice_item_price"],
            "invoice_item_qty"=>$invoiceItem["invoice_item_qty"],   
            "invoice_item_total_price"=>$invoiceItem["invoice_item_total_price"],
            "invoice_id"=>$invoiceId
        ];
        return InvoiceItem::insert($data);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Invoice  $invoice
     * @return \Illuminate\Http\Response
     */
    public function show(Invoice $invoice){
        try {
            $user = $this->request->user();
            $invoices = Invoice::where('user_id',$user->id)->get();
            return response()->json(['message'=>'Success','data'=>$invoices],200);
        } catch (\Exception $th) {
            return response()->json(['message'=>$th->getMessage()],400);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Invoice  $invoice
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Invoice $invoice)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Invoice  $invoice
     * @return \Illuminate\Http\Response
     */
    public function destroy($invoiceId){
        try {
            $invoice = Invoice::where('invoice_id',$invoiceId)->delete();
            if($invoice){
                return response()->json(['message'=>'Successfully deleted invoice by id '.$invoiceId],200);
            }else{
                return response()->json(['message'=>'Successfully not deleted invoice by id '.$invoiceId],404);
            }
        } catch (\Exception $th) {
            return response()->json(['message'=>$th->getMessage()],400);
        }
    }

    public function getInvoiceById($invoiceId)
    {
        try {
            $invoice = Invoice::where('invoice_id',$invoiceId)->get();
            $invoiceItem= InvoiceItem::where('invoice_id',$invoiceId)->get();
            $resultInvoice=array(
                "invoice_id"=>$invoice[0]->invoice_id,
                "created_at"=>$invoice[0]->created_at,
                "invoice_table"=>$invoice[0]->invoice_table,
                "invoice_sub_total"=>$invoice[0]->invoice_sub_total,
                "invoice_gst"=>$invoice[0]->invoice_gst,
                "invoice_total_price"=>$invoice[0]->invoice_total_price,
                "invoice_item"=>$invoiceItem
            );
            if($invoice){
                return response()->json(['message'=>'Successfully fetched invoice by id '.$invoiceId, 'data'=> $resultInvoice],200);
            }else{
                return response()->json(['message'=>'Successfully not fetched invoice by id '.$invoiceId],404);
            }
        } catch (\Exception $th) {
            return response()->json(['message'=>$th->getMessage()],400);
        }
    }

    public function validateStore(){
        return Validator::make(request()->all(), [
            'invoice_table' => 'required',
            'invoice_gst' => 'required',
            'invoice_items'   => 'required|array|min:1',
            'invoice_items.*.invoice_item_name' => 'required',
            'invoice_items.*.invoice_item_price' => 'required',
            'invoice_items.*.invoice_item_total_price' => 'required',
            'invoice_items.*.invoice_item_qty' => 'required'
        ],[
            'invoice_items.required'=>'Give at least one invoice items list'
        ]);
    }
}
