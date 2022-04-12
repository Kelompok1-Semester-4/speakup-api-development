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
        Schema::create('detail_users', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->string('name');
            $table->enum('gender', ['L', 'P']);
            $table->date('birth');
            $table->string('phone');
            $table->string('photo')->default("");
            $table->text('address')->default("");
            $table->string('job')->default("");
            $table->text('work_address')->default("");
            $table->text('practice_place_address')->default("");
            $table->string('office_phone_number')->default("");
            $table->integer('is_verified')->default(0);
            $table->string('benefits')->default("");
            $table->float('price')->default(0);
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
        Schema::dropIfExists('detail_users');
    }
};
