<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToTicketOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ticket_orders', function (Blueprint $table) {
            $table->foreign('banquet_id', 'ticket_orders_ibfk_1')->references('id')->on('banquets')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign('discount_id', 'ticket_orders_ibfk_2')->references('id')->on('discounts')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ticket_orders', function (Blueprint $table) {
            $table->dropForeign('ticket_orders_ibfk_1');
            $table->dropForeign('ticket_orders_ibfk_2');
        });
    }
}
