<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExpensesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->double("amount");
            $table->string("recurring_type")->nullable();
            $table->date("date");
            $table->date("repeat_date")->nullable();
            $table->text("description")->nullable();
            $table->unsignedBigInteger('category_id')->index()->nullable();
            $table->foreign("category_id")->references("id")->on("categories")->cascadeOnDelete();
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
        Schema::dropIfExists('expenses');
    }
}
