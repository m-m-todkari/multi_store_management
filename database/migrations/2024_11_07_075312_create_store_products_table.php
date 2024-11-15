<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStoreProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('store_products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('store_id');            
            $table->unsignedBigInteger('product_id');            
            $table->integer('quantity');
            $table->timestamps();
            $table->foreign('store_id')->references('id')->on('stores')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $table->dropForeign('lists_store_id_foreign');
        $table->dropIndex('lists_store_id_index');
        $table->dropColumn('store_id');
        $table->dropForeign('lists_product_id_foreign');
        $table->dropIndex('lists_product_id_index');
        $table->dropColumn('product_id');
        Schema::dropIfExists('store_products');
        
    }
}
