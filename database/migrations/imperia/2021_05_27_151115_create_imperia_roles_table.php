<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateImperiaRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('imperia_roles', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->string('name', 50)->unique('roles_unique_NAME');
            $table->string('description', 100)->nullable();
            $table->boolean('can_read')->default(1);
            $table->boolean('can_insert')->default(0);
            $table->boolean('can_modify')->default(0);
            $table->boolean('is_owner')->default(0);
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
        Schema::dropIfExists('imperia_roles');
    }
}
