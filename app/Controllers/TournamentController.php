<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Csrf;
use App\Core\Flash;
use App\Repositories\TeamRepository;
use App\Repositories\TournamentRepository;
use PDOException;

/**
 * Tournaments tab: list tournaments and create new ones.
 */
final class TournamentController extends Controller
{
    public function index(): void
    {
        $tournaments = new TournamentRepository();
        $teams = new TeamRepository();

        $this->view('tournaments', [
            'activePage'   => 'tournaments',
            'tournaments'  => $tournaments->allWithTeams(),
            'teams'        => $teams->all(),
        ]);
    }

    public function create(): void
    {
        Csrf::verify();

        $name = trim($_POST['tournament_name'] ?? '');
        $season = trim($_POST['season'] ?? '');
        $startDate = trim($_POST['start_date'] ?? '');
        $endDate = trim($_POST['end_date'] ?? '');
        $teamIds = array_map('intval', $_POST['team_ids'] ?? []);

        if ($name === '' || $season === '' || $startDate === '' || $endDate === '') {
            Flash::error('All tournament fields are required.');
            $this->redirect('tournaments');
        }

        if ($endDate < $startDate) {
            Flash::error('End date must be on or after the start date.');
            $this->redirect('tournaments');
        }

        try {
            $repository = new TournamentRepository();
            $repository->create($name, $season, $startDate, $endDate, $teamIds);

            Flash::success(sprintf("Tournament '%s (%s)' created.", $name, $season));
        } catch (PDOException $e) {
            Flash::error('Database error adding tournament: ' . $e->getMessage());
        }

        $this->redirect('tournaments');
    }
}
