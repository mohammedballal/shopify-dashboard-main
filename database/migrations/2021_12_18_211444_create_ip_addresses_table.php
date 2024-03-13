<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIpAddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ip_addresses', function (Blueprint $table) {
            $table->id();
            $table->enum('type',['manual','auto'])->nullable()->comment('IP block type, auto or manual');
            $table->enum('status',[0,1,2])->comment('0 for blocked, 1 for unlocked,2 for permanent whitelisted')->index();
            $table->string('ip_address')->comment("Login Request IP Address")->index();
            $table->integer("successful_logins")->nullable()->default(0);
            $table->integer("un_successful_logins")->nullable()->default(0);
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
        Schema::dropIfExists('ip_addresses');
    }
}
