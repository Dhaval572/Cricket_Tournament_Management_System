<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Csrf;
use App\Core\Flash;
use App\Repositories\MatchRepository;
use App\Repositories\TeamRepository;
use App\Repositories\TournamentRepository;
use PDOException;

/**
 * Matches tab: schedule list with format filter, plus add-match form.
 */
final class MatchController extends Controller
{
    /**
     * URL format parameter (any case) => canonical format value.
     */
    private const FORMAT_MAP = [
        'ALL' => 'ALL',
        'T20' => 'T20',
        'ODI' => 'ODI',
        'TEST' => 'Test',
    ];

    public function index(): void
    {
        $requested = strtoupper(trim($_GET['format'] ?? 'ALL'));
        $selectedFormat = self::FORMAT_MAP[$requested] ?? 'ALL';

        $matches = new MatchRepository();
        $teams = new TeamRepository();
        $tournaments = new TournamentRepository();

        $this->view('matches', [
            'activePage'     => 'matches',
            'matches'        => $matches->all($selectedFormat === 'ALL' ? null : $selectedFormat),
            'teams'          => $teams->all(),
            'tournaments'    => $tournaments->all(),
            'venues'         => $matches->venues(),
            'selectedFormat' => $selectedFormat,
        ]);
    }

    public function create(): void
    {
        Csrf::verify();

        $tournamentId = trim($_POST['tournament_id'] ?? '');
        $team1Id = trim($_POST['team1_id'] ?? '');
        $team2Id = trim($_POST['team2_id'] ?? '');
        $venueId = trim($_POST['venue_id'] ?? '');
        $format = trim($_POST['format'] ?? '');
        $matchDate = trim($_POST['match_date'] ?? '');
        $matchStatus = trim($_POST['match_status'] ?? 'Scheduled');
        $resultType = trim($_POST['result_type'] ?? '');
        $winnerTeamId = trim($_POST['winner_team_id'] ?? '');
        $resultSummary = trim($_POST['result_summary'] ?? '');

        if ($tournamentId === '' || $team1Id === '' || $team2Id === ''
            || $venueId === '' || $format === '' || $matchDate === '') {
            Flash::error('Please fill in all required match fields.');
            $this->redirect('matches');
        }

        if ($team1Id === $team2Id) {
            Flash::error('A team cannot play a match against itself.');
            $this->redirect('matches');
        }

        if ($matchStatus === 'Completed' && $resultType === '') {
            Flash::error('Result type is required for a completed match.');
            $this->redirect('matches');
        }

        if ($resultType === 'WIN' && $winnerTeamId === '') {
            Flash::error('Winner team is required when the result type is WIN.');
            $this->redirect('matches');
        }

        if ($winnerTeamId !== '' && !in_array($winnerTeamId, [$team1Id, $team2Id], true)) {
            Flash::error('Winner must be one of the two teams playing the match.');
            $this->redirect('matches');
        }

        try {
            $repository = new MatchRepository();
            $repository->create([
                'tournament_id'  => (int) $tournamentId,
                'team1_id'       => (int) $team1Id,
                'team2_id'       => (int) $team2Id,
                'venue_id'       => (int) $venueId,
                'format'         => $format,
                'match_date'     => $matchDate,
                'match_status'   => $matchStatus,
                'winner_team_id' => $winnerTeamId !== '' ? (int) $winnerTeamId : null,
                'result_type'    => $resultType !== '' ? $resultType : null,
                'result_summary' => $resultSummary !== '' ? $resultSummary : null,
            ]);

            Flash::success('Match scheduled/recorded in the database.');
        } catch (PDOException $e) {
            Flash::error('Database constraint error: ' . $e->getMessage());
        }

        $this->redirect('matches');
    }
}
