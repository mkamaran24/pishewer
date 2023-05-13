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
        Schema::create('category_trans', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('locale');
            $table->unsignedBigInteger('categ_id')->index();
            $table->foreign('categ_id')->references('id')->on('categories')->onDelete('cascade');
            $table->unique(['categ_id', 'locale']);
            $table->timestamps();
        });

        Schema::create('subcategory_trans', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('locale');
            // $table->unsignedBigInteger('categ_id')->index();
            $table->unsignedBigInteger('subcateg_id')->index();
            // $table->foreign('categ_id')->references('id')->on('categories')
            // ->onUpdate('cascade')
            // ->onDelete('cascade');
            $table->foreign('subcateg_id')->references('id')->on('subcategories')->onDelete('cascade');
            $table->unique(['subcateg_id', 'locale']);
            $table->timestamps();
        });

        Schema::create('job_trans', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string("title");
            $table->text('description');
            $table->string("price", 50);
            $table->string("completein", 50);
            $table->string('locale');
            $table->unsignedBigInteger('job_id')->index();
            $table->foreign('job_id')->references('id')->on('jobs')->onDelete('cascade');
            $table->unique(['job_id', 'locale']);
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
        Schema::dropIfExists('categories_trans');
        Schema::dropIfExists('subcategories_trans');
        Schema::dropIfExists('jobs_trans');
    }
};
