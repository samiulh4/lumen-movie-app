<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMoviesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('movies', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title', 150);
            $table->year('released_year');
            $table->date('released_date')->nullable();
            $table->text('description')->nullable();
            $table->string('picture', 355)->default('assets/img/poster-not-available.jpg')->nullable();
            $table->string('cover', 355)->default('assets/img/default-movie-cover.jpg')->nullable();
            $table->bigInteger('language_id')->unsigned()->nullable();
            $table->bigInteger('country_id')->unsigned()->nullable();
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
        Schema::dropIfExists('movies');
    }
}
