<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('game_sessions', function (Blueprint $table) {
            $table->id();
            $table->integer('player_id');
            $table->enum('game_type', ['slots', 'poker', 'blackjack']);
            $table->decimal('wager_amount', 10, 2);
            $table->decimal('net_result', 10, 2);
            $table->timestamp('session_start_at');
            $table->timestamps();
            
            $table->index('player_id');
            $table->index('game_type');
            $table->index('session_start_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('game_sessions');
    }
};