<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSpacesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('spaces', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->string('name', 50);
            $table->smallInteger('number');
            $table->smallInteger('floor');
            $table->string('description', 100)->nullable();
            $table->decimal('price')->unsigned();
            $table->unsignedSmallInteger('category_id')->index('category_id');
            $table->unsignedInteger('period_id')->nullable()->index('period_id');
            $table->timestamps();
            $table->softDeletes();
            $table->unique(['name', 'number', 'floor', 'category_id'], 'spaces_unique_NAME_and_NUMBER_and_FLOOR_in_CATEGORY');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('spaces');
    }
}
