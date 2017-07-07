<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBanerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('banner', function (Blueprint $table) {
            $table->increments('banner_id');
            $table->text('banner_title')->nullable();
            $table->text('banner_link')->nullable();
            $table->longText('banner_media')->nullable();
            $table->integer('banner_order_no')->nullable();
            $table->integer('banner_status')->nullbale();
            $table->integer('banner_created')->nullable();
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
        Schema::dropIfExists('banner');
    }
}
