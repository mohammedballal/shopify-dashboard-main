<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId("shop_id")->index()->constrained()->cascadeOnDelete();
            $table->foreignId("user_id")->index()->nullable()->constrained();
            $table->string("store_currency")->nullable()->default(NULL);
            $table->string("order_no")->index()->unique();
            $table->dateTime("order_date")->index();
            $table->double("total")->nullable()->default(NULL);
            $table->double("total_usd")->nullable()->default(NULL);
            $table->string("customer_first_name")->nullable()->default(NULL);
            $table->string("customer_last_name")->nullable()->default(NULL);
            $table->string("customer_name")->nullable()->index()->default(NULL);
            $table->string("payment_status")->nullable()->default(NULL);
            $table->string("fulfillment_status")->nullable()->default(NULL);
            $table->integer("items_count")->nullable()->default(NULL);
            $table->longText("items_array")->nullable()->default(NULL);
            $table->string("delivery_method")->nullable()->default(NULL);
            $table->text("tags")->nullable()->default(NULL);
            $table->longText("order_api_response")->nullable()->default(NULL);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
