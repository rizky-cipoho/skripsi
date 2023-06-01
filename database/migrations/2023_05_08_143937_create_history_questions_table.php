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
        Schema::create('history_questions', function (Blueprint $table) {
           $table->string('id')->primary();
           $table->string('history_id');
           $table->integer('line');
           $table->integer('lineBlock');
           $table->string('question_id');
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
        Schema::dropIfExists('history_questions');
    }
};
