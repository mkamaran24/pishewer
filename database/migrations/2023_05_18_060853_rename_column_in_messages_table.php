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

        Schema::table('messages', function ($table) {
            $table->dropForeign(['job_list_msg_id']);
        });
        
        Schema::table('messages', function ($table) {
            $table->dropColumn('job_list_msg_id');
        });

        Schema::table('messages', function (Blueprint $table) {
            $table->text('ftm_code')->after('recever_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

    }
};
