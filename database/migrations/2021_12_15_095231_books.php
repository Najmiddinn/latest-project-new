<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Books extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('books', function (Blueprint $table) {
            $table->id()->unique()->unsigned()->autoIncrement();
            $table->integer('category');
            $table->integer('book_code');
            $table->integer('book_count');
            $table->string('title')->length(255);
            $table->string('author')->length(150);
            $table->string('publisher')->length(200);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('books');
    }
}
