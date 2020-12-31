<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoiceItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoice_items', function (Blueprint $table) {
            $table->bigIncrements('invoice_item_id');
            $table->string('invoice_item_name');
            $table->string('invoice_item_price');
            $table->bigInteger('invoice_item_qty');
            $table->string('invoice_item_total_price');
            $table->bigInteger('invoice_id')->unsigned()->index();
            $table->timestamps();
            $table->foreign('invoice_id')
                    ->references('invoice_id')
                    ->on('invoices')
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
        Schema::dropIfExists('invoice_items');
    }
}
