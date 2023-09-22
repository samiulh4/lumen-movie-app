<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersAuthLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users_auth_logs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamp('sign_in_date')->nullable();
            $table->timestamp('sign_out_date')->nullable();
            $table->string('auth_token', 500)->nullable();
            $table->string('auth_token_type', 10)->nullable();
            $table->string('auth_token_expires_in', 255)->nullable();
            $table->string('client_ip', 100)->nullable();//logical address
            $table->bigInteger('created_by')->unsigned()->nullable();
            $table->bigInteger('updated_by')->unsigned()->nullable();
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
        Schema::dropIfExists('users_auth_logs');
    }
}
