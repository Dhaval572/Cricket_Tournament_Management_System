<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Repositories\StatsRepository;

/**
 * Player statistics tab: batting and bowling leaderboards.
 */
final class StatsController extends Controller
{
    public function index(): void
    {
        $stats = new StatsRepository();

        $this->view('stats', [
            'activePage'    => 'stats',
            'battingStats'  => $stats->battingLeaders(),
            'bowlingStats'  => $stats->bowlingLeaders(),
        ]);
    }
}
