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
        Schema::create('profiles', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->string('skills');
            $table->string('langs');
            $table->string('certification');
            $table->string('nationalid');
            $table->unsignedBigInteger('city_id')->index();
            $table->foreign('city_id')->references('id')->on('cities')
            ->onUpdate('cascade')
            ->onDelete('cascade');
            $table->string('age');
            $table->string('gender');
            $table->unsignedBigInteger('user_id')->index()->uniqid();
            $table->foreign('user_id')->references('id')->on('users')
            ->onUpdate('cascade')
            ->onDelete('cascade');
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
        Schema::dropIfExists('profiles');
    }
};
