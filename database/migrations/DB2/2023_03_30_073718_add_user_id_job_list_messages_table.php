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
        //
        Schema::table('job_list_messages', function (Blueprint $table) {
            //
            $table->integer('user_id')->default(0)->after('job_id');
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
        Schema::table('job_list_messages', function (Blueprint $table) {
            //
            $table->dropColumn('user_id');
        });
    }
};
