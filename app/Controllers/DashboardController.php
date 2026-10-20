<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Repositories\MatchRepository;
use App\Repositories\StatsRepository;

/**
 * Landing page: summary cards, recent results, upcoming fixtures.
 */
final class DashboardController extends Controller
{
    public function index(): void
    {
        $stats = new StatsRepository();
        $matches = new MatchRepository();

        $this->view('dashboard', [
            'activePage'       => 'dashboard',
            'counts'           => $stats->counts(),
            'completedMatches' => $matches->recentCompleted(10),
            'upcomingMatches'  => $matches->upcoming(10),
        ]);
    }
}
