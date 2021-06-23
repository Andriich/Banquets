<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToPeriodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('periods', function (Blueprint $table) {
            $table->foreign('beg_datetime_id', 'periods_ibfk_1')->references('id')->on('datetimes')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign('end_datetime_id', 'periods_ibfk_2')->references('id')->on('datetimes')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('periods', function (Blueprint $table) {
            $table->dropForeign('periods_ibfk_1');
            $table->dropForeign('periods_ibfk_2');
        });
    }
}
