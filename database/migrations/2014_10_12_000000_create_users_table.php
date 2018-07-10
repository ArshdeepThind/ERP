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
            $table->string('facebook_id',50)->nullable();
            $table->string('google_id',50)->nullable();
            $table->string('firstname',30);
            $table->string('lastname',30);
            $table->string('email',50);
            $table->string('password',50);
            $table->string('phone',15);
            $table->enum('gender',array('Male','Female'))->default('Male');
            $table->tinyInteger('status')->default('1');
            $table->tinyInteger('is_verified')->default('0');
            $table->tinyInteger('is_reset')->default('0');
            $table->string('pic')->default('avatar.png');
            $table->float('wallet');
            $table->float('credit');
            $table->string('device_token')->nullable();
            $table->string('device_type')->nullable();
            $table->string('build_version')->nullable();
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
