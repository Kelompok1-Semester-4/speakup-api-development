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
        Schema::create('detail_courses', function (Blueprint $table) {
            $table->id();
            $table->integer('course_id')->unsigned();
            $table->string('title');
            $table->text('description');
            $table->string('video_link')->nullable();
            $table->string('cover_image');
            $table->string('duration')->nullable();
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
        Schema::dropIfExists('detail_courses');
    }
};
