<?php

namespace App\Http\Controllers;

use App\Models\Store;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Store::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (auth()->user()->role == "admin") {
            $request->validate([
                'name' => 'required',
                'location' => 'required',
                'contact_info' => 'required|regex:/^[0-9]+$/'
            ]);

            return Store::create($request->all());

        } else {
            return response(array('message'=>'You are not authorized for this action'),403);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Store  $store
     * @return \Illuminate\Http\Response
     */
    public function show(Store $store)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Store  $store
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Store $store)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Store  $store
     * @return \Illuminate\Http\Response
     */
    public function destroy(Store $store)
    {
        //
    }


    public function productStockStatus(){

        return Store::with('storeProducts.products')->get();

    }


    public function storeSales(Request $request){
        $request->validate([
            'store_id' => 'integer|exists:stores,id',
            'start_date' => 'date|before_or_equal:end_date',
            'end_date' => 'date|after_or_equal:start_date',
        ]);
        $store_id = $request['store_id'];
        $start_date = date('Y-m-d', strtotime($request['start_date']));
        $end_date = date('Y-m-d', strtotime($request['end_date']));
        return Store::select('stores.id as store_id','stores.name as store_name','stores.location','contact_info','odr.id as order_id','odr.status','odr.total_price','odr.created_at as order_date')
        ->from('stores')
        ->join('orders as odr','store_id','=','stores.id')
        ->join('users as usr','usr.id','=','odr.user_id')
        ->whereDate('odr.created_at','>=',$start_date)
        ->whereDate('odr.created_at','<=',$end_date)
        ->where('stores.id',$store_id)->get();
    }
}
