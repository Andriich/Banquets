<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBanquetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('banquets', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 50);
            $table->string('description', 100)->nullable();
            $table->decimal('advance_amount')->unsigned();
            $table->dateTime('beg_datetime');
            $table->dateTime('end_datetime');
            $table->unsignedTinyInteger('state_id')->index('state_id');
            $table->unsignedSmallInteger('creator_id')->index('creator_id');
            $table->unsignedInteger('customer_id')->index('customer_id');

            $table->dateTime('paid_at')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('banquets');
    }
}
