<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersWpsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders_wps', function (Blueprint $table) {
            $table->id();
            $table->string("order_no")->unique();
            $table->dateTime("date_created");
            $table->string("status");
            $table->double("total");
            $table->longText("order_object");
            $table->string("customer_note")->nullable();
            $table->string("currency")->nullable();
            $table->double("total_tax")->nullable();
            $table->string("payment_method")->nullable();
            $table->integer("line_items")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders_wps');
    }
}
