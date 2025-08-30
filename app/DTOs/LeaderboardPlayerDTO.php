<?php

namespace App\DTOs;

class LeaderboardPlayerDTO
{
    public function __construct(
        public readonly int $rank,
        public readonly int $playerId,
        public readonly string $username,
        public readonly float $totalWagered,
        public readonly float $totalNetResult,
        public readonly int $performanceScore
    ) {}
}