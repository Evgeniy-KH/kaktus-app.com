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
        Schema::create('dish_images', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('dish_id');
            $table->string('image')->nullable();
            $table->unsignedSmallInteger('type_id')->nullable();
            $table->timestamps();
            $table->index('dish_id','dish_images_dish_idx');
            $table->foreign('dish_id', 'dish_images_dish_fk')->on('dishes')->references('id');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('dish_images');
    }
};
