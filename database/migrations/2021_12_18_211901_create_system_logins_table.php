<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSystemLoginsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('system_logins', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ip_address_id')->comment("Login Request IP Address From ip_addresses Table")->index()->constrained();
            $table->enum('login_status',[0,1,2])->comment('0 for Unsuccessful, 1 for Successful, 2 for In-active Try')->index();
            // Logged In User ID ( optional )
            $table->foreignId('user_id')->nullable()->constrained()->cascadeOnDelete();
            $table->string('user_login_email')->index()->comment('user email used for login');
            $table->text('user_login_pass_hash')->comment('user pass used for login');
            $table->text('referer_url')->nullable();
            $table->text('user_session_id')->nullable();
            $table->text('user_agent')->nullable();
            $table->text('quick_logout_reason')->nullable()->default(NULL);
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
        Schema::dropIfExists('system_logins');
    }
}
