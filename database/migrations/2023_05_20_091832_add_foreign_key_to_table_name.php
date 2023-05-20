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
        Schema::table('offer_addons', function (Blueprint $table) {
            //
            $table->unsignedBigInteger('offer_id')->index();

            $table->foreign('offer_id')
                ->references('id')
                ->on('offers')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('offer_addons', function (Blueprint $table) {
            //
            $table->dropForeign(['offer_id']);
            $table->dropColumn('offer_id');
        
        });
    }
};
