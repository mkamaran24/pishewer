<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Remove the foreign key constraint
        Schema::table('jobs', function ($table) {
            $table->dropForeign(['subcateg_id']);
        });

        // Remove the subcateg_id column
        Schema::table('jobs', function ($table) {
            $table->dropColumn('subcateg_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
};
