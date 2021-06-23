<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToServiceOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('service_orders', function (Blueprint $table) {
            $table->foreign('banquet_id', 'service_orders_ibfk_1')->references('id')->on('banquets')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign('discount_id', 'service_orders_ibfk_2')->references('id')->on('discounts')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('service_orders', function (Blueprint $table) {
            $table->dropForeign('service_orders_ibfk_1');
            $table->dropForeign('service_orders_ibfk_2');
        });
    }
}
