<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateModulTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('module', function (Blueprint $table) {
        $table->increments('module_id');
        $table->string('module_title')->nullable();
        $table->string('module_controller')->nullable();
        $table->longText('module_action')->nullable();
        $table->tinyInteger('module_status')->nullable();
        $table->integer('module_order_no')->nullable();
        $table->integer('module_created')->nullable();
//            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('modul');
    }
}
