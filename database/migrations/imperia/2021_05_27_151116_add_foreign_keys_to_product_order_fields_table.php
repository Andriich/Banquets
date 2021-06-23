<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToProductOrderFieldsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('product_order_fields', function (Blueprint $table) {
            $table->foreign('order_id', 'product_order_fields_ibfk_1')->references('id')->on('product_orders')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign('product_id', 'product_order_fields_ibfk_2')->references('id')->on('products')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('product_order_fields', function (Blueprint $table) {
            $table->dropForeign('product_order_fields_ibfk_1');
            $table->dropForeign('product_order_fields_ibfk_2');
        });
    }
}
