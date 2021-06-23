<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 50)->unique('product_categories_unique_NAME');
            $table->string('description', 100)->nullable();
            $table->decimal('price')->unsigned();
            $table->decimal('weight')->unsigned()->nullable();
            $table->unsignedSmallInteger('menu_id')->index('menu_id');
            $table->unsignedSmallInteger('category_id')->index('category_id');
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
        Schema::dropIfExists('products');
    }
}
