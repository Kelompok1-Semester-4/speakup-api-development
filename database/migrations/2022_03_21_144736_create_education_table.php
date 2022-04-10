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
        Schema::create('education', function (Blueprint $table) {
            $table->id();
            $table->integer('detail_user_id')->unsigned();
            $table->enum('level', ['SMA', 'SMK', 'S1', 'S2', 'S3']);
            $table->string('institution');
            $table->string('address');
            $table->string('major');
            $table->string('study_field')->nullable();
            $table->date('graduation_year');
            $table->string('gpa');
            $table->longText('file_url')->nullable();
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
        Schema::dropIfExists('education');
    }
};
