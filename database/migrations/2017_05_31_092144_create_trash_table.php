<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTrashTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trash', function (Blueprint $table) {
        $table->increments('trash_id');
        $table->integer('user_id')->nullable();
        $table->integer('trash_obj_id')->nullable();
        $table->string('trash_title')->nullable();
        $table->string('trash_class')->nullable();
        $table->longText('trash_content')->nullable();
        $table->longText('trash_one_media')->nullable();
        $table->longText('trash_multi_media')->nullable();
        $table->string('trash_folder_media')->nullable();
        $table->integer('trash_created')->nullable();
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
        Schema::dropIfExists('trash');
    }
}
