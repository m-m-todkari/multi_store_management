<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id');            
            $table->unsignedBigInteger('product_id');
            $table->integer('quantity');
            $table->decimal('price',5,2);
            $table->timestamps();
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
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
        $table->dropForeign('lists_order_id_foreign');
        $table->dropIndex('lists_order_id_index');
        $table->dropColumn('order_id');
        $table->dropForeign('lists_product_id_foreign');
        $table->dropIndex('lists_product_id_index');
        $table->dropColumn('product_id');
        Schema::dropIfExists('order_items');
    }
}
