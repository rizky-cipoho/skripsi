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
        Schema::create('history_choices', function (Blueprint $table) {
          $table->string('id')->primary();
          $table->string('choice_id');
          $table->string('history_question_id');
          $table->string('question_id');
          $table->integer('line');
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
        Schema::dropIfExists('history_choices');
    }
};
