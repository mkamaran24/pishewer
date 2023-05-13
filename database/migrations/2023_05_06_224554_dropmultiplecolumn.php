<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn('name'); // drop the column
        });
        Schema::table('subcategories', function (Blueprint $table) {
            $table->dropColumn('name'); // drop the column
        });
        Schema::table('jobs', function (Blueprint $table) {
            $table->dropColumn('title'); // drop the column
            $table->dropColumn('description'); // drop the column
            $table->dropColumn('price'); // drop the column
            $table->dropColumn('completein'); // drop the column
        });
    }
    
    public function down()
    {

    }
    
};
