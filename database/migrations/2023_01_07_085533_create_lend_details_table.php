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
        Schema::create('lend_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('lend_id');
            $table->unsignedBigInteger('book_id');
            $table->integer('qty');
            $table->timestamps();

            $table->foreign('lend_id')->references('id')->on('lends');
            $table->foreign('book_id')->references('id')->on('books');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lend_details');
    }
};
