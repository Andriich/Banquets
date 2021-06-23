<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToSpacesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('spaces', function (Blueprint $table) {
            $table->foreign('category_id', 'spaces_ibfk_1')->references('id')->on('space_categories')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign('period_id', 'spaces_ibfk_2')->references('id')->on('periods')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('spaces', function (Blueprint $table) {
            $table->dropForeign('spaces_ibfk_1');
            $table->dropForeign('spaces_ibfk_2');
        });
    }
}
