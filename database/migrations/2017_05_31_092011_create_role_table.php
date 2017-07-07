<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRoleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('role', function (Blueprint $table) {
        $table->increments('role_id');
        $table->string('	role_title')->nullable();
        $table->longText('role_permission')->nullable();
        $table->integer('role_order_no')->nullable();
        $table->tinyInteger('role_status')->nullable();
        $table->integer('role_created')->nullable();
        $table->tinyInteger('allow_upload')->nullable();
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
        Schema::dropIfExists('role');
    }
}
