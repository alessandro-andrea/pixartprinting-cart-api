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
        Schema::create('carts', function (Blueprint $table) {
            //region Columns
            $table->id();
            $table->integer('ecommerce_id');
            $table->integer('customer_id');
            $table->enum('status', [
                'created',
                'building',
                'checkout'
            ])->default('created');
            $table->date('date_checkout')->nullable(true);
            $table->integer('price')->default(0);
            $table->timestamps();
            //endregion

            //region Keys and Indexes
            $table->index(['ecommerce_id', 'customer_id']);
            $table->index('status');
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
        Schema::dropIfExists('carts');
    }
};
