<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Repositories\StatsRepository;

/**
 * Standings tab: live points tables per tournament.
 */
final class StandingsController extends Controller
{
    public function index(): void
    {
        $stats = new StatsRepository();

        $this->view('standings', [
            'activePage' => 'standings',
            'standings'  => $stats->standingsGroupedByTournament(),
        ]);
    }
}
