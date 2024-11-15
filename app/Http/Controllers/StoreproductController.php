<?php

namespace App\Http\Controllers;
use App\Models\Storeproduct;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class StoreproductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Storeproduct::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Storeproduct::where('store_id',$id)->get();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'product_id' => 'required',
            'quantity' => 'required|integer|min:1'
        ]);
        $store_product = Storeproduct::where(array('store_id'=>$id,'product_id'=>$request['product_id']))->first();
        if($store_product){
            
           Storeproduct::where(array('store_id'=>$id,'product_id'=>$request['product_id']))->update($request->all());
           $response = [
                'message' => 'Store Product Updated Successfully'
           ];
        }else{
            $request['store_id'] = $id;
            Storeproduct::create($request->all());
            $response = [
                'message' => 'Store Product Added Successfully'
            ];
        }
        return response($response,201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }



    
}
