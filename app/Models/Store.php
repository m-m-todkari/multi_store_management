<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name','location','contact_info'
    ];

    public function orders(){
        return $this->hasMany(Orders::class,'store_id','id');
    }

    public function products(){
        return $this->hasMany(products::class,'store_id','id');
    }

    public function storeProducts(){
        return $this->hasMany(Storeproduct::class,'store_id','id');
    }
}
