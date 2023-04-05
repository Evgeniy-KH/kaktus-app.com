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
        Schema::create('favorite_dishes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('dish_id');

            $table->index('dish_id','favorite_dishes_dish_idx');
            $table->foreign('dish_id', 'favorite_dishes_dish_fk')->on('dishes')->references('id')->onDelete('cascade');
            $table->index('user_id','favorite_dishes_user_idx');
            $table->foreign('user_id', 'favorite_dishes_user_fk')->on('users')->references('id')->onDelete('cascade');

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
        Schema::dropIfExists('favorite_dishes');
    }
};
