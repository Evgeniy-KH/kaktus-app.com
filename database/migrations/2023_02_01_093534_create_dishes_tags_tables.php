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
        Schema::create('dishes_tags', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('dish_id');
            $table->unsignedSmallInteger('tag_id');
            $table->timestamps();
            $table->index('dish_id','recipe_tag_dish_idx');
            $table->foreign('dish_id', 'recipe_tag_dish_fk')->on('dishes')->references('id');
            $table->index('tag_id','recipe_tag_tag_idx');
            $table->foreign('tag_id', 'recipe_tag_tag_fk')->on('tags')->references('id');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('dishes_tags_tables');
    }
};
