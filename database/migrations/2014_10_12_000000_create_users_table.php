<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_role_id')->nullable();
            $table->string('name')->nullable();
            $table->string('user_address')->nullable();
            $table->string('user_phone')->nullable();
            $table->integer('user_last_login')->nullable();
            $table->string('user_last_ip')->nullable();
            $table->tinyInteger('user_status')->nullable();
            $table->string('email')->unique()->nullable();
            $table->string('password')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
