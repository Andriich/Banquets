<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToSpaceOrderFieldsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('space_order_fields', function (Blueprint $table) {
            $table->foreign('order_id', 'space_order_fields_ibfk_1')->references('id')->on('space_orders')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign('space_id', 'space_order_fields_ibfk_2')->references('id')->on('spaces')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('space_order_fields', function (Blueprint $table) {
            $table->dropForeign('space_order_fields_ibfk_1');
            $table->dropForeign('space_order_fields_ibfk_2');
        });
    }
}
