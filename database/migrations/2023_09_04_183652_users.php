<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Users extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 50);
            $table->string('email', 100)->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password', 255);
            $table->string('user_type', 5)->default('5x501');
            $table->string('avatar', 255)->default('assets/img/default-user.jpg')->nullable();
            $table->enum('gender', ['m', 'f', 't', 'o'])->nullable();
            $table->tinyInteger('is_active')->default(0);
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
