<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToBanquetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('banquets', function (Blueprint $table) {
            $table->foreign('state_id', 'banquets_ibfk_1')->references('id')->on('banquet_states')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign('creator_id', 'banquets_ibfk_2')->references('id')->on('imperia_users')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign('customer_id', 'banquets_ibfk_3')->references('id')->on('customers')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('banquets', function (Blueprint $table) {
            $table->dropForeign('banquets_ibfk_1');
            $table->dropForeign('banquets_ibfk_2');
            $table->dropForeign('banquets_ibfk_3');
        });
    }
}
