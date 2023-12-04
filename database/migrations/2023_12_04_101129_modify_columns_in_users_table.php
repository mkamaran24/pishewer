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
        Schema::table('users', function (Blueprint $table) {
            //
            // $table->dropColumn('fullname');
            // $table->dropColumn('fastpay_acc_num');
            // $table->dropColumn('phone_number');
            $table->string('fastpay_acc_num')->nullable()->after("email");
            $table->string('phone_number')->nullable()->after("password");
        });

        Schema::table('user_translations', function (Blueprint $table) {
            //
            // $table->dropColumn('fullname');
            $table->string('fullname')->nullable()->after("username");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
};
