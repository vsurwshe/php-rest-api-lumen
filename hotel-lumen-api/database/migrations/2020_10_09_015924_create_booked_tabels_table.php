<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookedTabelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('booked_tabels', function (Blueprint $table) {
            $table->bigIncrements('booked_tabel_id');
            $table->string('booked_tabel_name');
            $table->string('booked_tabel_customer_size');
            $table->bigInteger('table_id')->unsigned()->index();
            $table->timestamps();
            $table->foreign('table_id')
                    ->references('table_id')
                    ->on('hotel_tables')
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
        Schema::dropIfExists('booked_tabels');
    }
}
