<?php

namespace App\Services;

use App\DTOs\LeaderboardPlayerDTO;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;
use \Exception;
use \Closure;

class PromotionService
{
    protected ?Closure $onFailure = null;

    /**
     * Allows developers to hook into a failed response.
     *
     * @param Closure $callback The function to run on failure.
     * @return self
     */
    public function onFailure(Closure $callback): self
    {
        $this->onFailure = $callback;
        return $this;
    }

    /**
     * Fetches and calculates the promotional leaderboard data for a given period.
     *
     * @param Carbon $startDate The start date of the promotional window.
     * @param Carbon $endDate The end date of the promotional window.
     * @param int $bonusPoints The number of bonus points for a positive net result.
     * @return array An array of LeaderboardPlayerDTO objects.
     * @throws Exception
     */
    public function getLeaderboard(
        Carbon $startDate,
        Carbon $endDate,
        int $bonusPoints = 500
    ): array {
        // Dynamic cache key based on the parameters
        $cacheKey = "promotional_leaderboard_{$startDate->toDateString()}_{$endDate->toDateString()}_{$bonusPoints}";
        $cacheDuration = 60; // Cache for 60 minutes

        // Return cached data if it exists
        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        try {
            // The raw query from Task 1 with named placeholders
            $query = "
                WITH player_weekly_activity AS (
                    SELECT
                        player_id,
                        SUM(wager_amount) AS total_wagered,
                        SUM(net_result) AS total_net_result,
                        SUM(
                            CASE
                                WHEN game_type = 'slots' THEN wager_amount * 1
                                WHEN game_type IN ('poker', 'blackjack') THEN wager_amount * 2
                                ELSE 0
                            END
                        ) AS base_points
                    FROM game_sessions
                    WHERE session_start_at >= :start_date AND session_start_at < :end_date
                    GROUP BY player_id
                ),
                player_scores AS (
                    SELECT
                        player_id,
                        total_wagered,
                        total_net_result,
                        FLOOR(
                            base_points +
                            CASE
                                WHEN total_net_result > 0 THEN :bonus_points
                                ELSE 0
                            END
                        ) AS performance_score
                    FROM player_weekly_activity
                )
                SELECT
                    DENSE_RANK() OVER (ORDER BY ps.performance_score DESC) AS player_rank,
                    p.player_id,
                    p.username,
                    ps.total_wagered,
                    ps.total_net_result,
                    ps.performance_score
                FROM player_scores ps
                JOIN players p ON ps.player_id = p.player_id
                ORDER BY player_rank;
            ";

            // Prepare the named bindings for the query
            $bindings = [
                'start_date' => $startDate,
                'end_date' => $endDate,
                'bonus_points' => $bonusPoints
            ];

            $results = DB::select($query, $bindings);

            // Map the raw results to your DTO
            $leaderboard = array_map(function ($player) {
                return new LeaderboardPlayerDTO(
                    rank: $player->player_rank,
                    playerId: $player->player_id,
                    username: $player->username,
                    totalWagered: (float) $player->total_wagered,
                    totalNetResult: (float) $player->total_net_result,
                    performanceScore: (int) $player->performance_score
                );
            }, $results);

            // Store the result in the cache
            Cache::put($cacheKey, $leaderboard, $cacheDuration);

            return $leaderboard;

        } catch (Exception $e) {
            Log::error('PromotionService failed: ' . $e->getMessage(), ['exception' => $e]);

            if ($this->onFailure) {
                call_user_func($this->onFailure, $e);
            }

            throw $e;
        }
    }
}