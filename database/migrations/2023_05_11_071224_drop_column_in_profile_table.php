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
        Schema::table('profiles', function (Blueprint $table) {
            //
            $table->dropColumn('title');
            $table->dropColumn('description');
            $table->dropColumn('skills');
            $table->dropColumn('langs');
            $table->dropColumn('certification');
            $table->dropColumn('age');
            $table->dropColumn('gender');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('profile', function (Blueprint $table) {
            $table->string('title');
            $table->text('description');
            $table->string('skills');
            $table->string('langs');
            $table->string('certification');
            $table->string('age');
            $table->string('gender');
        });
    }
};
