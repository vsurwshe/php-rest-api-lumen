<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRoomBooking extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('room_booking', function (Blueprint $table) {
            $table->bigIncrements('room_booking_id');
            $table->dateTime('check_in_date');
            $table->dateTime('check_out_date');
            $table->bigInteger('room_booking_customer_size');
            $table->bigInteger('room_booking_subtotal');
            $table->bigInteger('room_booking_gst');
            $table->bigInteger('room_booking_total');
            $table->bigInteger('customer_id')->unsigned()->index();
            $table->bigInteger('room_id')->unsigned()->index();
            $table->bigInteger('user_id')->unsigned()->index();
            $table->timestamps();
            $table->foreign('user_id')
                    ->references('id')
                    ->on('users')
                    ->onDelete('cascade');
            $table->foreign('customer_id')
                    ->references('customer_id')
                    ->on('customers')
                    ->onDelete('cascade');
            $table->foreign('room_id')
                    ->references('room_id')
                    ->on('rooms')
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
        Schema::dropIfExists('room_booking');
    }
}
