<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('carts_items', function (Blueprint $table) {
            //region Columns
            $table->id();
            $table->integer('cart_id');
            $table->string('product_sku');
            $table->string('product_name');
            $table->string('file_type')->default('');
            $table->integer('quantity');
            $table->integer('price');
            $table->date('delivery_date');
            //endregion

            //region Indexes
            $table->index('cart_id');
            //endregion
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('carts_items');
    }
};
