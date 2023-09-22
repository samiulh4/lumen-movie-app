<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCountriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('countries', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title', 100);
            $table->string('nice_name_en', 100)->nullable();
            $table->string('nice_name', 100)->nullable();// name in language
            $table->string('iso2', 2)->nullable();
            $table->string('iso3', 3)->nullable();
            $table->string('num_code', 6)->nullable();
            $table->string('phone_code', 5)->nullable();
            $table->tinyInteger('is_active')->default(1);
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
        Schema::dropIfExists('countries');
    }
}
