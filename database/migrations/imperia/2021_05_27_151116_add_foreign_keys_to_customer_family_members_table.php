<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToCustomerFamilyMembersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('customer_family_members', function (Blueprint $table) {
            $table->foreign('customer_id', 'customer_family_members_ibfk_1')->references('id')->on('customers')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('customer_family_members', function (Blueprint $table) {
            $table->dropForeign('customer_family_members_ibfk_1');
        });
    }
}
