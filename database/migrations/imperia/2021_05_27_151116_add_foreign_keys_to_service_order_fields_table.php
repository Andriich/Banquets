<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToServiceOrderFieldsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('service_order_fields', function (Blueprint $table) {
            $table->foreign('order_id', 'service_order_fields_ibfk_1')->references('id')->on('service_orders')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign('service_id', 'service_order_fields_ibfk_2')->references('id')->on('services')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('service_order_fields', function (Blueprint $table) {
            $table->dropForeign('service_order_fields_ibfk_1');
            $table->dropForeign('service_order_fields_ibfk_2');
        });
    }
}
