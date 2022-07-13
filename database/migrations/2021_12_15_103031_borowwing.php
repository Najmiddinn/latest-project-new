<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Borowwing extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('borowwing', function (Blueprint $table) {
            $table->id()->unique()->unsigned()->autoIncrement();
            $table->integer('student_id');
            $table->integer('book_id');
            $table->tinyInteger('status');
            $table->date('date_borrowwed')->format('d.m.Y');
            $table->date('date_return')->format('d.m.Y');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('borowwing');
    }
}
