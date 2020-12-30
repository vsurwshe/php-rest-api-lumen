<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHotelTablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hotel_tables', function (Blueprint $table) {
            $table->bigIncrements('table_id');
            $table->string('table_name');
            $table->bigInteger('table_customer_size');
            $table->string('table_direction');
            $table->bigInteger('table_booked');
            $table->bigInteger('user_id')->unsigned()->index();;
            $table->timestamps();
            $table->foreign('user_id')
                    ->references('id')
                    ->on('users')
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
        Schema::dropIfExists('hotel_tables');
    }
}
