<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product', function (Blueprint $table) {
            $table->increments('product_id');
            $table->string('product_code')->nullable();
            $table->string('product_alias')->nullable();
            $table->integer('product_cate_id')->nullable();
            $table->integer('product_brand_id')->nullable();
            $table->string('product_title')->nullable();
            $table->integer('product_price_input')->nullable();
            $table->integer('product_price')->nullable();
            $table->integer('product_price_saleof')->nullable();
            $table->text('product_intro')->nullable();
            $table->longText('product_details')->nullable();
            $table->integer('product_order_no')->nullable();
            $table->text('product_media')->nullable();
            $table->text('product_multi_media')->nullable();
            $table->text('product_video')->nullable();
            $table->text('product_vote')->nullable();
            $table->tinyInteger('product_status')->nullable();
            $table->tinyInteger('product_cheapest')->nullable();
            $table->tinyInteger('product_gif')->nullable();
            $table->tinyInteger('product_most')->nullable();
            $table->tinyInteger('product_news')->nullable();
            $table->tinyInteger('product_buy_most')->nullable();
            $table->tinyInteger('product_best')->nullable();
            $table->integer('product_created')->nullable();
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
        Schema::dropIfExists('product');
    }
}
