<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToDiscountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('discounts', function (Blueprint $table) {
            $table->foreign('category_id', 'discounts_ibfk_1')->references('id')->on('discount_categories')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign('period_id', 'discounts_ibfk_2')->references('id')->on('periods')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('discounts', function (Blueprint $table) {
            $table->dropForeign('discounts_ibfk_1');
            $table->dropForeign('discounts_ibfk_2');
        });
    }
}
