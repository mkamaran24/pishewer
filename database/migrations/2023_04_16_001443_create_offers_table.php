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
        Schema::create('offers', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('price');
            $table->dateTime('delivery_date');
            $table->boolean('status')->default(false);
            $table->unsignedBigInteger('seller_id')->index();
            $table->foreign('seller_id')->references('id')->on('users')
            ->onUpdate('cascade')
            ->onDelete('cascade');
            $table->unsignedBigInteger('buyer_id')->index();
            $table->foreign('buyer_id')->references('id')->on('users')
            ->onUpdate('cascade')
            ->onDelete('cascade');
            $table->unsignedBigInteger('job_id')->index();
            $table->foreign('job_id')->references('id')->on('jobs')
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
        Schema::dropIfExists('offers');
    }
};
