<?php

namespace App\Http\Controllers;

use App\Services\PromotionService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Carbon\Carbon;
use Exception;
use Illuminate\Pagination\LengthAwarePaginator;

class PromotionController extends Controller
{
    public function __construct(private PromotionService $promotionService) {}

    public function getLeaderboard(Request $request): JsonResponse
    {
        try {
            $startDate = Carbon::parse('2025-06-18 00:00:00');
            $endDate = Carbon::parse('2025-06-25 00:00:00');
            $bonusAmount = 500;

            $forceRefresh = $request->boolean('refresh', false);

            // Get all data from DB or Service class cache (Laravel Cache)
            $allPlayers = $this->promotionService->getLeaderboard(
                $startDate,
                $endDate,
                $bonusAmount,
                $forceRefresh
            );

            // Pagination parameters
            $page = $request->get('page', 1);
            $perPage = $request->get('per_page', 20);
            
            // Make an array for pagination
            $playersArray = array_map(function ($player) {
                return [
                    'player_rank' => $player->rank,
                    'player_id' => $player->playerId,
                    'username' => $player->username,
                    'total_wagered' => $player->totalWagered,
                    'total_net_result' => $player->totalNetResult,
                    'performance_score' => $player->performanceScore
                ];
            }, $allPlayers);

            // Paginator
            $paginator = new LengthAwarePaginator(
                array_slice($playersArray, ($page - 1) * $perPage, $perPage),
                count($playersArray),
                $perPage,
                $page,
                ['path' => $request->url(), 'query' => $request->query()]
            );

            return response()->json([
                'data' => $paginator->items(),
                'meta' => [
                    'current_page' => $paginator->currentPage(),
                    'per_page' => $paginator->perPage(),
                    'total' => $paginator->total(),
                    'total_pages' => $paginator->lastPage(),
                    'from' => $paginator->firstItem(),
                    'to' => $paginator->lastItem(),
                ],
                'links' => [
                    'first' => $paginator->url(1),
                    'last' => $paginator->url($paginator->lastPage()),
                    'prev' => $paginator->previousPageUrl(),
                    'next' => $paginator->nextPageUrl(),
                ]
                ]);
            //->header('Cache-Control', 'public, max-age=300')
            //->header('ETag', md5(serialize($allPlayers) . $page . $perPage));;

        } catch (Exception $e) {
            return response()->json(['error' => 'Could not retrieve leaderboard data.'], 500);
        }
    }   
}