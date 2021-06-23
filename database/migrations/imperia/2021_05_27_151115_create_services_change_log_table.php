<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServicesChangeLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('services_change_log', function (Blueprint $table) {
            $table->unsignedSmallInteger('service_id');
            $table->decimal('once_paid_price')->unsigned()->nullable();
            $table->decimal('hourly_paid_price')->unsigned()->nullable();
            $table->dateTime('created_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('services_change_log');
    }
}
