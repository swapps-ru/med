<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameBodyPartsToBodySystems extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('disease_groups', function (Blueprint $table) {
            $table->renameColumn('body_part_ids', 'body_system_ids');

            $table->dropColumn('slug'); //влом создавать ещё одну миграцию, пусть тут будет
        });

        Schema::table('diseases', function (Blueprint $table) {
            $table->renameColumn('body_part_ids', 'body_system_ids');
        });

        Schema::table('symptoms', function (Blueprint $table) {
            $table->renameColumn('body_part_ids', 'body_system_ids');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('disease_groups', function (Blueprint $table) {
            $table->renameColumn('body_system_ids', 'body_part_ids');
        });

        Schema::table('diseases', function (Blueprint $table) {
            $table->renameColumn('body_system_ids', 'body_part_ids');
        });

        Schema::table('symptoms', function (Blueprint $table) {
            $table->renameColumn('body_system_ids', 'body_part_ids');
        });
    }
}
