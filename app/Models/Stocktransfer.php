<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stocktransfer extends Model
{
    use HasFactory;


    protected $table = 'stock_transfers';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'from_store_id','to_store_id','product_id','quantity','transfer_date','status'
    ];

    public function fromstore(){

        return $this->hasOne(Store::class,'id','from_store_id');
    }
    public function tostore(){

        return $this->hasOne(Store::class,'id','to_store_id');
    }
    public function products(){

        return $this->hasOne(Products::class,'id','product_id');
    }
}
