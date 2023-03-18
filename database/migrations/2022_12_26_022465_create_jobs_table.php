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
        Schema::create('jobs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string("title");
            $table->text('description');
            $table->string("price",50);
            $table->string("completein",50);
            $table->boolean('status')->default(false);
            $table->unsignedBigInteger('user_id')->index();
            $table->unsignedBigInteger('categ_id')->index();
            $table->unsignedBigInteger('subcateg_id')->index();
            $table->foreign('categ_id')->references('id')->on('categories')
            ->onUpdate('cascade')
            ->onDelete('cascade');
            $table->foreign('subcateg_id')->references('id')->on('subcategories')
            ->onUpdate('cascade')
            ->onDelete('cascade');
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
        Schema::dropIfExists('jobs');
    }
};
