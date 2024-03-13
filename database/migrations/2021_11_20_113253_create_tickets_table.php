<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTicketsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->index()->comment('user who initiated the ticket');
            $table->unsignedBigInteger('agent_id')->index()->nullable()->default(null)->comment('user who is assigned to the ticket');
            $table->text('description');
            $table->enum('status',[0,1,2])->comment('0:Open, 1:InProgress, 2:Closed')->default(0);
            $table->enum('priority',[0,1,2])->comment('0:low, 1:medium, 2:high')->default(0);
            $table->timestamps();

            $table->foreign("user_id")->references("id")->on("users")->cascadeOnDelete();
            $table->foreign("agent_id")->references("id")->on("users");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tickets');
    }
}
