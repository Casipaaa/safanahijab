<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('details', function (Blueprint $table) {
            $table->id();
            $table->integer('quantity');
            $table->decimal('price', 10, 2);
            $table->timestamps();

            $table->foreignId('user_id')->references('id')->on('users');
            $table->foreignId('order_id')->references('id')->on('orders');
            $table->foreignId('product_id')->references('id')->on('products');
        });
    }

    public function down()
    {
        Schema::dropIfExists('details');
    }
};
