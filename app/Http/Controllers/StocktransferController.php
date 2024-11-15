<?php

namespace App\Http\Controllers;

use App\Models\Stocktransfer;
use App\Models\Storeproduct;
use App\Models\Products;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StocktransferController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Stocktransfer::with('fromstore','tostore','products')->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {   //echo "<pre>"; print_r($request['transfer_date']); die;
        if (auth()->user()->role == "manager") {
            $request->validate([
                'from_store_id' => 'required',
                'to_store_id' => 'required',
                'product_id' => 'required',
                'quantity'  => 'required|integer|min:1',
                'transfer_date' => 'required'
            ]);
            
            $storeData = Storeproduct::select('quantity')->where(array('store_id'=>$request['from_store_id'],'product_id'=>$request['product_id']))->first();
            $request['transfer_date'] = date('Y-m-d', strtotime($request['transfer_date']));
            if($storeData['quantity']>$request['quantity']){
                return Stocktransfer::create($request->all());
            }else{
                return response(array('message'=>'Insufficient product quantity'),201);
            }

        } else {
            return response(array('message'=>'You are not authorized for this action'),403);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Stocktransfer  $stocktransfer
     * @return \Illuminate\Http\Response
     */
    public function show(Stocktransfer $stocktransfer)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Stocktransfer  $stocktransfer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Stocktransfer $stocktransfer)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Stocktransfer  $stocktransfer
     * @return \Illuminate\Http\Response
     */
    public function destroy(Stocktransfer $stocktransfer)
    {
        //
    }


    public function updateStockTransfer(Request $request){
        if (auth()->user()->role == "admin") {
            if($request['status'] == 'Approved'){
                $request->validate([
                    'order_id' => 'required',
                    'status' => 'required'
                ]);
                $orderData = Stocktransfer::find($request['order_id']);
                $fromStoreData = Storeproduct::select('store_id','product_id','quantity')->where(array('store_id'=>$orderData['from_store_id'],'product_id'=>$orderData['product_id']))->first();
                $toStoreData = Storeproduct::select('store_id','product_id','quantity')->where(array('store_id'=>$orderData['to_store_id'],'product_id'=>$orderData['product_id']))->first();

                if($fromStoreData['quantity']>$orderData['quantity']){
                    $fromStoreData = $fromStoreData->attributesToArray();
                    Stocktransfer::where('id',$request['order_id'])->update(['status'=>$request['status']]);
                    $fromStoreData['quantity'] = $fromStoreData['quantity'] - $orderData['quantity'];
                    $this->updateStoreProduct($fromStoreData);
                    if($toStoreData){
                        $toStoreData = $toStoreData->attributesToArray();
                        $toStoreData['quantity'] = $toStoreData['quantity'] + $orderData['quantity'];
                    }else{
                        $toStoreData = [];
                        $toStoreData['store_id'] = $orderData['to_store_id'];
                        $toStoreData['product_id'] = $orderData['product_id'];
                        $toStoreData['quantity'] = $orderData['quantity'];
                    }
                    $this->updateStoreProduct($toStoreData);
                    return response(array('message'=>'Request approved successfully'),200);
                }else{
                    return response(array('message'=>'Insufficient product quantity'),201);
                }
            }else{
                Stocktransfer::where('id',$request['order_id'])->update(['status'=>$request['status']]);
                return response(array('message'=>'Request '.$request['status'].' successfully'),200);
            }
        } else {
            return response(array('message'=>'You are not authorized for this action'),403);
        }




    }

    public function updateStoreProduct($data){
        $store_product = Storeproduct::where(array('store_id'=>$data['store_id'],'product_id'=>$data['product_id']))->first();
        if($store_product){
            Storeproduct::where(array('store_id'=>$data['store_id'],'product_id'=>$data['product_id']))->update($data);
            $message = 'Store Product Updated Successfully';
        }else{
            Storeproduct::create($data);
            $message = 'Store Product Added Successfully';
        }
        return $message;
    }


    public function reportOfStockTransfer(Request $request){
        $request->validate([
            'start_date' => 'date|before_or_equal:end_date',
            'end_date' => 'date|after_or_equal:start_date',
        ]);
        $start_date = date('Y-m-d', strtotime($request['start_date']));
        $end_date = date('Y-m-d', strtotime($request['end_date']));
        return DB::table('stock_transfers as st')
                    ->join('stores as fs','st.from_store_id','=','fs.id')
                    ->join('stores as ts','st.to_store_id','=','ts.id')
                    ->join('products as prdt','st.product_id','=','prdt.id')
                    ->whereDate('st.transfer_date','>=',$start_date)
                    ->whereDate('st.transfer_date','<=',$end_date)
                    ->select('st.from_store_id','st.to_store_id','st.product_id','st.quantity as total_quantity'
                            ,'st.transfer_date','st.status','fs.name as fs_name','fs.contact_info as fs_contact'
                            ,'ts.name as ts_name','ts.contact_info as ts_contact','prdt.name as product_name','prdt.price',DB::raw('st.quantity * prdt.price as total_amount'))
                        
                    ->get();



    }
}
