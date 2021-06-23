<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToImperiaMenusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('imperia_menus', function (Blueprint $table) {
            $table->foreign('period_id', 'imperia_menus_ibfk_1')->references('id')->on('periods')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign('category_id', 'imperia_menus_ibfk_2')->references('id')->on('menu_categories')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('imperia_menus', function (Blueprint $table) {
            $table->dropForeign('imperia_menus_ibfk_1');
            $table->dropForeign('imperia_menus_ibfk_2');
        });
    }
}
