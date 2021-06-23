<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('services', function (Blueprint $table) {
            $table->foreign('category_id', 'services_ibfk_1')->references('id')->on('service_categories')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign('period_id', 'services_ibfk_2')->references('id')->on('periods')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('services', function (Blueprint $table) {
            $table->dropForeign('services_ibfk_1');
            $table->dropForeign('services_ibfk_2');
        });
    }
}
