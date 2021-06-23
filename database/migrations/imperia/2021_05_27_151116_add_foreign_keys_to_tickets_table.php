<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToTicketsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->foreign('category_id', 'tickets_ibfk_1')->references('id')->on('ticket_categories')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign('period_id', 'tickets_ibfk_2')->references('id')->on('periods')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->dropForeign('tickets_ibfk_1');
            $table->dropForeign('tickets_ibfk_2');
        });
    }
}
