<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ElektronBooks extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('elektron_books', function (Blueprint $table) {
            $table->id()->unique()->unsigned()->autoIncrement();
            $table->integer('category');
            $table->string('title')->length(255);
            $table->string('description')->length(500);
            $table->string('file')->length(500);
            $table->string('extension')->length(100);
            $table->float('size');
            $table->tinyInteger('status');
            $table->timestamps();
        });
    }
//2021_12_21_080505_elektron_books
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('elektron_books');
    }
}
