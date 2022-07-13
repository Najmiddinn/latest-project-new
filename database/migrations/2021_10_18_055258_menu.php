<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class Menu extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('menu', function (Blueprint $table) {
            $table->id()->unique()->unsigned()->autoIncrement();
            $table->string('name')->length(200);
            $table->string('url')->length(255);
            $table->tinyInteger('type');
            $table->integer('parent')->nullable();
            $table->smallInteger('order_by')->nullable();
            $table->boolean('status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        
        // DB::unprepared('ALTER TABLE `menu` CONVERT TO CHARACTER SET utf8');
        Schema::dropIfExists('menu');
    }
}
