<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderFoodTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_food', function (Blueprint $table) {
            $table->bigIncrements('order_food_id');
            $table->string('order_food_name');
            $table->string('order_food_unit_price');
            $table->bigInteger('order_food_qty');
            $table->string('order_food_total_price');
            $table->bigInteger('booked_tabel_id')->unsigned()->index();
            $table->timestamps();
            $table->foreign('booked_tabel_id')
            ->references('booked_tabel_id')
            ->on('booked_tabels')
            ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_food');
    }
}
