<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Csrf;
use App\Core\Flash;
use App\Repositories\TeamRepository;
use PDOException;

/**
 * Teams tab: registered teams and the add-team form.
 */
final class TeamController extends Controller
{
    public function index(): void
    {
        $teams = new TeamRepository();

        $this->view('teams', [
            'activePage' => 'teams',
            'teams'      => $teams->allWithCounts(),
        ]);
    }

    public function create(): void
    {
        Csrf::verify();

        $teamName = trim($_POST['team_name'] ?? '');
        $country = trim($_POST['country'] ?? '');

        if ($teamName === '' || $country === '') {
            Flash::error('Both Team Name and Country are required.');
            $this->redirect('teams');
        }

        try {
            $repository = new TeamRepository();
            $repository->create($teamName, $country);

            Flash::success(sprintf("Team '%s' added to the database.", $teamName));
        } catch (PDOException $e) {
            if ($e->getCode() === '23000') {
                Flash::error(sprintf("Team '%s' already exists.", $teamName));
            } else {
                Flash::error('Database error adding team: ' . $e->getMessage());
            }
        }

        $this->redirect('teams');
    }
}
