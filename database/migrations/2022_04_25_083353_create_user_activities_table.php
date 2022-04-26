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
        Schema::create('user_activities', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('global_activity_id')->nullable();
            $table->unsignedBigInteger('user_id');
            $table->string('activity_title');
            $table->text('activity_description');
            $table->string('activity_image')->nullable();
            $table->date('on_date');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('global_activity_id')->references('id')->on('users')->onDelete('set null');
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
        Schema::dropIfExists('user_activities');
    }
};
