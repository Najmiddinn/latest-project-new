<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class BookCategory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('book_category', function (Blueprint $table) {
            $table->id()->unique()->unsigned()->autoIncrement();
            $table->integer('parent');
            $table->string('name')->length(100);
            $table->tinyInteger('status');
            $table->integer('order_by');
        });
    }
    // 2021_12_21_080043_book_category
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('book_category');
    }
}
