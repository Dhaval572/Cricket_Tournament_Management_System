<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Csrf;
use App\Core\Flash;
use App\Repositories\PlayerRepository;
use App\Repositories\TeamRepository;
use PDOException;

/**
 * Players tab: player list with current team and registration form.
 */
final class PlayerController extends Controller
{
    public function index(): void
    {
        $players = new PlayerRepository();
        $teams = new TeamRepository();

        $this->view('players', [
            'activePage' => 'players',
            'players'    => $players->allWithCurrentTeam(),
            'teams'      => $teams->all(),
        ]);
    }

    public function create(): void
    {
        Csrf::verify();

        $playerName = trim($_POST['player_name'] ?? '');
        $dateOfBirth = trim($_POST['date_of_birth'] ?? '');
        $battingStyle = trim($_POST['batting_style'] ?? '');
        $bowlingStyle = trim($_POST['bowling_style'] ?? '') ?: 'None';
        $teamId = trim($_POST['team_id'] ?? '');
        $startDate = trim($_POST['start_date'] ?? '') ?: date('Y-m-d');

        if ($playerName === '' || $dateOfBirth === '' || $battingStyle === '') {
            Flash::error('Player Name, Date of Birth, and Batting Style are required.');
            $this->redirect('players');
        }

        $parsedDate = \DateTime::createFromFormat('Y-m-d', $dateOfBirth);
        $isValidDate = $parsedDate !== false
            && $parsedDate->format('Y-m-d') === $dateOfBirth;

        if (!$isValidDate) {
            Flash::error('Date of Birth must be a valid calendar date (YYYY-MM-DD).');
            $this->redirect('players');
        }

        try {
            $repository = new PlayerRepository();
            $repository->create(
                $playerName,
                $dateOfBirth,
                $battingStyle,
                $bowlingStyle,
                $teamId !== '' ? (int) $teamId : null,
                $startDate
            );

            Flash::success(sprintf("Player '%s' added.", $playerName));
        } catch (PDOException $e) {
            Flash::error('Database error adding player: ' . $e->getMessage());
        }

        $this->redirect('players');
    }
}
