<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCategoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('category', function (Blueprint $table) {
            $table->increments('category_id');
            $table->integer('category_parent_id')->nullable();
            $table->string('category_keyword')->nullable();
            $table->string('category_title')->nullable();
            $table->text('category_intro')->nullable();
            $table->text('category_media')->nullable();
            $table->text('category_media_banner')->nullable();
            $table->tinyInteger('category_status')->nullable();
            $table->tinyInteger('horizontal_menu')->nullable();
            $table->tinyInteger('vertical_menu')->nullable();
            $table->integer('category_order_no')->nullable();
            $table->integer('category_created')->nullable();
            $table->string('meta_title')->nullable();
            $table->string('meta_keywords')->nullable();
            $table->text('meta_description')->nullable();
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
        Schema::dropIfExists('category');
    }
}
