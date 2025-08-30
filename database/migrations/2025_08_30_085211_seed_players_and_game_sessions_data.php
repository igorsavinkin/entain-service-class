<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Insert players data
        DB::table('players')->insert([
            ['player_id' => 101, 'username' => 'ace_player', 'created_at' => now(), 'updated_at' => now()],
            ['player_id' => 102, 'username' => 'lady_luck', 'created_at' => now(), 'updated_at' => now()],
            ['player_id' => 103, 'username' => 'jackpot_joe', 'created_at' => now(), 'updated_at' => now()],
            ['player_id' => 104, 'username' => 'inactive_ivy', 'created_at' => now(), 'updated_at' => now()],
        ]);

        // Insert game sessions data
        DB::table('game_sessions')->insert([
            // Player 101: High wager on table games, positive net result
            [
                'player_id' => 101,
                'game_type' => 'poker',
                'wager_amount' => 500.00,
                'net_result' => 250.00,
                'session_start_at' => '2025-06-20 10:00:00',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'player_id' => 101,
                'game_type' => 'blackjack',
                'wager_amount' => 300.00,
                'net_result' => -50.00,
                'session_start_at' => '2025-06-21 11:00:00',
                'created_at' => now(),
                'updated_at' => now()
            ],
            // Player 102: High wager on slots, negative net result
            [
                'player_id' => 102,
                'game_type' => 'slots',
                'wager_amount' => 1200.00,
                'net_result' => -150.00,
                'session_start_at' => '2025-06-22 14:00:00',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'player_id' => 102,
                'game_type' => 'poker',
                'wager_amount' => 100.00,
                'net_result' => 25.00,
                'session_start_at' => '2025-06-22 15:00:00',
                'created_at' => now(),
                'updated_at' => now()
            ],
            // Player 103: Lower wager, but will end up with the same score as Player 102
            [
                'player_id' => 103,
                'game_type' => 'blackjack',
                'wager_amount' => 700.00,
                'net_result' => -200.00,
                'session_start_at' => '2025-06-23 18:00:00',
                'created_at' => now(),
                'updated_at' => now()
            ],
            // Session outside the 7-day window
            [
                'player_id' => 101,
                'game_type' => 'slots',
                'wager_amount' => 100.00,
                'net_result' => 10.00,
                'session_start_at' => '2025-06-10 09:00:00',
                'created_at' => now(),
                'updated_at' => now()
            ],
            // Session for player who registered but has no recent activity
            [
                'player_id' => 104,
                'game_type' => 'slots',
                'wager_amount' => 50.00,
                'net_result' => -50.00,
                'session_start_at' => '2025-05-01 12:00:00',
                'created_at' => now(),
                'updated_at' => now()
            ],
        ]);
    }

    public function down(): void
    {
        DB::table('game_sessions')->truncate();
        DB::table('players')->truncate();
    }
};