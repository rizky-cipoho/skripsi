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
        Schema::create('exams', function(Blueprint $table){
            $table->string('id')->primary();
            $table->string('exam');
            $table->string('lesson_id');
            $table->string('other')->nullable();
            $table->integer('choice');
            $table->integer('minimum')->unique();
            $table->string('uni_code')->unique();
            $table->string('key')->nullable();
            $table->string('time_id');
            $table->text('description')->nullable();
            $table->string('remove')->nullable();
            $table->string('tier');
            $table->string('user_id');
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
        Schema::dropIfExists('exams');
    }
};
