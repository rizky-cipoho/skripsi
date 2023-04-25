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
        Schema::create('question_data', function(Blueprint $table){
            $table->string('id')->primary();
            $table->text('data')->nullable();
            $table->string('type');
            $table->string('remove')->nullable();
            $table->string('question_id');
            $table->string('question_attachment')->nullable();
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
        Schema::dropIfExists('question_data');
    }
};
