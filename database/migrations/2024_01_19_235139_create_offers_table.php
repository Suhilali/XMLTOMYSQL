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
            $table->string('identifier');
            $table->string('name');
            $table->boolean('available');
            $table->string('url');
            $table->integer('price');
            $table->integer('oldprice');
            $table->integer('currency_id');
            $table->integer('SubSubCategory_id')->nullable();
            $table->char('picture', 250);
            $table->string('vendor');
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
