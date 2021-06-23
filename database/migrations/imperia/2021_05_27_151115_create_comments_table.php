<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->unsignedSmallInteger('id')->default(0);
            $table->string('text', 100);
            $table->unsignedInteger('target_id');
            $table->string('target_type', 35);
            $table->unsignedInteger('container_id');
            $table->string('container_type', 35);
            $table->dateTime('created_at')->useCurrent();
            $table->dateTime('updated_at')->useCurrent()->useCurrentOnUpdate();
            $table->primary(['id', 'container_type', 'container_id']);
        });

        DB::unprepared('
            create trigger trigger_comments_before_insert
                before insert
                on comments
                for each row
            begin
                if (new.id = 0) then
                    select max(id)
                    into @id
                    from comments
                    where container_type = new.container_type
                      and container_id = new.container_id;

                    if (@id is null) then
                        set new.id = 1;
                    else
                        set new.id = @id + 1;
                    end if;

                end if;
            end;
        ');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('comments');
        DB::unprepared('drop trigger trigger_comments_before_insert');
    }
}
