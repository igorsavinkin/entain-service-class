<?php

namespace App\Http\Controllers;

use App\Services\PromotionService;
use Illuminate\Http\JsonResponse;
use Carbon\Carbon;
use \Exception;

class PromotionController extends Controller
{
    public function __construct(private PromotionService $promotionService) {}

    public function getLeaderboard(): JsonResponse
    {
        try {
            // Define the parameters for the leaderboard promotion
            // In a real app, these could come from the request or a config file
            $startDate = Carbon::parse('2025-06-18 00:00:00');
            $endDate = Carbon::parse('2025-06-25 00:00:00');
            $bonusAmount = 500; // The standard bonus

            $leaderboardData = $this->promotionService->getLeaderboard(
                $startDate,
                $endDate,
                $bonusAmount
            );

            // Convert DTO objects to array format expected by React app
            $formattedData = array_map(function ($player) {
                return [
                    'player_rank' => $player->rank,
                    'player_id' => $player->playerId,
                    'username' => $player->username,
                    'total_wagered' => $player->totalWagered,
                    'total_net_result' => $player->totalNetResult,
                    'performance_score' => $player->performanceScore
                ];
            }, $leaderboardData);

            return response()->json($formattedData);
        } catch (Exception $e) {
            // Return a user-friendly error response
            return response()->json(['error' => 'Could not retrieve leaderboard data.'], 500);
        }
    }

    // One could easily create another endpoint for a different promotion
    public function getHighStakesLeaderboard(): JsonResponse
    {
        try {
            // Example: A different promotion with a higher bonus
            $startDate = Carbon::now()->startOfWeek();
            $endDate = Carbon::now()->endOfWeek();
            $highStakesBonus = 1000;

            $leaderboardData = $this->promotionService->getLeaderboard(
                $startDate,
                $endDate,
                $highStakesBonus
            );

            // Convert DTO objects to array format expected by React app
            $formattedData = array_map(function ($player) {
                return [
                    'player_rank' => $player->rank,
                    'player_id' => $player->playerId,
                    'username' => $player->username,
                    'total_wagered' => $player->totalWagered,
                    'total_net_result' => $player->totalNetResult,
                    'performance_score' => $player->performanceScore
                ];
            }, $leaderboardData);

            return response()->json($formattedData);
        } catch (Exception $e) {
            return response()->json(['error' => 'Could not retrieve leaderboard data.'], 500);
        }
    }
}
