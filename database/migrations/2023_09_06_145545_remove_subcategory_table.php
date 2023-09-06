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
        Schema::table('subcategory_trans', function ($table) {
            $table->dropForeign(['subcateg_id']);
        });
        Schema::dropIfExists('subcategories');
        Schema::dropIfExists('subcategory_trans');
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
