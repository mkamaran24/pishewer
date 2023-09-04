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
        Schema::create('freelance_community_trans', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('story');
            $table->string('locale');
            $table->unsignedBigInteger('freelance_community_id')->index();
            $table->foreign('freelance_community_id')->references('id')->on('freelance_comunities')->onDelete('cascade')->onUpdate('cascade');
            $table->unique(['freelance_community_id', 'locale']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('freelance_community_trans');
    }
};
