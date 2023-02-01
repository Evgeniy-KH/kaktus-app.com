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
        Schema::create('recipe_images', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('recipe_id');
            $table->string('image')->nullable();
            $table->unsignedSmallInteger('type_id')->nullable();
            $table->timestamps();
            $table->index('recipe_id','recipe_images_recipe_idx');
            $table->foreign('recipe_id', 'recipe_images_recipe_fk')->on('recipes')->references('id');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('recipe_images');
    }
};
