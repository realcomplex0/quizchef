<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('lobbies', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("code");
            $table->unsignedBigInteger("quiz_host");
            $table->unsignedBigInteger("quiz_id");
            $table->unsignedBigInteger("status");
            $table->unsignedBigInteger("next_question");
            $table->unsignedBigInteger("question_start_time")->nullable();
            $table->foreign('quiz_host')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
            $table->foreign('quiz_id')
                ->references('id')
                ->on('quizzes')
                ->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lobbies');
    }
};
