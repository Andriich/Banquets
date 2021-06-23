<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePeriodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('periods', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('beg_datetime_id')->nullable()->index('beg_datetime_id');
            $table->unsignedInteger('end_datetime_id')->nullable()->index('end_datetime_id');
            $table->string('weekdays', 13)->nullable();
            $table->boolean('is_templatable')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('periods');
    }
}
