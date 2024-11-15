<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Storeproduct extends Model
{
    use HasFactory;

    protected $table = 'store_products';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'store_id','product_id','quantity'
    ];

    public function store(){

        return $this->hasOne(Store::class,'id','store_id');
    }
    public function products(){

        return $this->hasOne(Products::class,'id','product_id');
    }
}
