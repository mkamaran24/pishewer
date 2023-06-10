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
        Schema::table('category_trans', function (Blueprint $table) {
            //
            $table->string('description')->after('name');
            $table->boolean('popular')->default(false)->after('locale');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('category_trans', function (Blueprint $table) {
            //
            $table->dropColumn('description');
            $table->dropColumn('popular');
        });
    }
};
