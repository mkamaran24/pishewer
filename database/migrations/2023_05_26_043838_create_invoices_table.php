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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('offer_id')->index();
            $table->foreign('offer_id')
                ->references('id')
                ->on('offers')
                ->onUpdate('cascade');
            $table->unsignedBigInteger('seller_id')->index();
            $table->foreign('seller_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
            $table->string('offer_amount');
            $table->string('status');
            
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
        Schema::dropIfExists('invoices');
    }
};
