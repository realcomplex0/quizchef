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
        Schema::create('lobby_players', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("lobby_id");
            $table->string('session_id');
            $table->string('nickname');
            $table->boolean('is_host');
            $table->bigInteger("score");
            $table->boolean('has_answered');
            $table->foreign('lobby_id')
                ->references('id')
                ->on('lobbies')
                ->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lobby_players');
    }
};
