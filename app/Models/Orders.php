<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Orders extends Model
{
    use HasFactory;


     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id','store_id','status','total_price'
    ];



    public function users(){
        return $this->hasone(User::class ,'id','user_id');
    }

    public function orderitems(){
        return $this->hasMany(Orderitems::class,'order_id','id');
    }
    
    public function store(){
        return $this->hasone(Store::class,'id','store_id');
    } 
}
