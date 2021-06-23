<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateImperiaUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('imperia_users', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->unsignedSmallInteger('role_id')->index('role_id');
            $table->string('name', 50)->unique('users_unique_NAME');
            $table->string('password', 50);
            $table->string('api_token', 64);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('imperia_users');
    }
}
