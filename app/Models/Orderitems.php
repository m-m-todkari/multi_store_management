<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Orderitems extends Model
{
    use HasFactory;

    protected $table = 'order_items';


     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'order_id','product_id','quantity','price'
    ];

    public function order(){

        return $this->hasOne(Orders::class,'id','order_id');
    }
    public function products(){

        return $this->hasOne(Products::class,'id','product_id');
    }
}
