<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToTicketOrderFieldsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ticket_order_fields', function (Blueprint $table) {
            $table->foreign('order_id', 'ticket_order_fields_ibfk_1')->references('id')->on('ticket_orders')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign('ticket_id', 'ticket_order_fields_ibfk_2')->references('id')->on('tickets')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ticket_order_fields', function (Blueprint $table) {
            $table->dropForeign('ticket_order_fields_ibfk_1');
            $table->dropForeign('ticket_order_fields_ibfk_2');
        });
    }
}
