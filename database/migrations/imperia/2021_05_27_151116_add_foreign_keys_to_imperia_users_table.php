<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToImperiaUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('imperia_users', function (Blueprint $table) {
            $table->foreign('role_id', 'imperia_users_ibfk_1')->references('id')->on('imperia_roles')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('imperia_users', function (Blueprint $table) {
            $table->dropForeign('imperia_users_ibfk_1');
        });
    }
}
