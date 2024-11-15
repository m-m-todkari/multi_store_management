<?php

namespace App\Http\Controllers;

use App\Models\Orders;
use App\Models\Orderitems;
use App\Models\User;
use App\Models\Store;
use App\Models\Storeproduct;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class OrdersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Orders::with('orderitems')->where('user_id',auth()->user()->id)->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $store_id = $request['store_id'];
        $order_items = $request['order_items'];
        $totalPrice = 0;
        foreach($order_items as $key => $value){
            $storeProduct = Storeproduct::with('products')->where(array('store_id'=>$store_id,'product_id'=>$value['product_id']))->first();
            if($storeProduct){
                if($storeProduct['quantity']>$value['quantity']){
                    $price = $value['quantity'] * $value['price'];
                    $totalPrice = $totalPrice + $price;
                    $insertOrderItemData[$key]['product_id'] = $value['product_id'];
                    $insertOrderItemData[$key]['quantity'] = $value['quantity'];
                    $insertOrderItemData[$key]['price'] = $value['price'];
                }else{
                    return response(array('message'=>'Insufficient '.$storeProduct['name'].' product quantity'),201);
                }
            }else{
                return response(array('message'=>'Some products are not available in this store'),201);
            }

        }
        	 
        $insertOrderData['user_id'] = auth()->user()->id;
        $insertOrderData['store_id'] = $store_id;
        $insertOrderData['total_price'] = $totalPrice;
        $order = Orders::Create($insertOrderData);
        foreach($insertOrderItemData as $key => $value){
            $insertOrderItemData[$key]['order_id'] = $order['id'];
        }
        $orderItems = Orderitems::insert($insertOrderItemData);
        return response(array('message'=>'Order created successfully'),200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Orders  $orders
     * @return \Illuminate\Http\Response
     */
    public function show($orderId)
    {
        return Orders::with('users','orderitems','store')->where('id',$orderId)->get();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Orders  $orders
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $orderId)
    {
        $request->validate([
            'status' => 'required'
        ]);
        $res = Orders::where('id',$orderId)->update(array('status'=>$request['status']));
        if($res){
            $message = "Order status updated successfully";
            $status = 200;
        }else{
            $message = "Something went wrong please try again in sometime";
            $status = 201;
        }
        return response(array('message'=>$message),$status);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Orders  $orders
     * @return \Illuminate\Http\Response
     */
    public function destroy(Orders $orders)
    {
        //
    }
}
