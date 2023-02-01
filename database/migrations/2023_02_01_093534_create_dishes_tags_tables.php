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
        Schema::create('recipes_tags', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('recipe_id');
            $table->unsignedSmallInteger('tag_id');
            $table->timestamps();
            $table->index('recipe_id','recipe_tag_recipe_idx');
            $table->foreign('recipe_id', 'recipe_tag_recipe_fk')->on('recipes')->references('id');
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
        Schema::dropIfExists('recipes_tags_tables');
    }
};
