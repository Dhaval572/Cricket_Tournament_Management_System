<?php

namespace App\Repositories;

use App\Core\Database;
use PDO;

/**
 * Data access for tournaments and their participating teams.
 */
final class TournamentRepository
{
    private PDO $db;

    public function __construct(?PDO $db = null)
    {
        $this->db = $db ?? Database::connect();
    }

    /**
     * Plain tournament list (used by the add-match form).
     */
    public function all(): array
    {
        $stmt = $this->db->query(
            'SELECT tournament_id, tournament_name, season
             FROM tournaments
             ORDER BY start_date DESC'
        );

        return $stmt->fetchAll();
    }

    /**
     * Tournaments with a comma separated list of participating teams.
     */
    public function allWithTeams(): array
    {
        $stmt = $this->db->query(
            'SELECT tr.tournament_id,
                    tr.tournament_name,
                    tr.season,
                    tr.start_date,
                    tr.end_date,
                    GROUP_CONCAT(t.team_name ORDER BY t.team_name SEPARATOR \', \') AS participating_teams,
                    COUNT(tt.team_id) AS team_count
             FROM tournaments tr
             LEFT JOIN tournamentteams tt ON tr.tournament_id = tt.tournament_id
             LEFT JOIN teams t            ON tt.team_id = t.team_id
             GROUP BY tr.tournament_id, tr.tournament_name, tr.season,
                      tr.start_date, tr.end_date
             ORDER BY tr.start_date DESC'
        );

        return $stmt->fetchAll();
    }

    /**
     * Create a tournament and register every selected team.
     * One transaction: tournament row plus all team links.
     *
     * @param int[] $teamIds
     */
    public function create(
        string $tournamentName,
        string $season,
        string $startDate,
        string $endDate,
        array $teamIds
    ): void {
        $this->db->beginTransaction();

        try {
            $stmt = $this->db->prepare(
                'INSERT INTO tournaments (tournament_name, season, start_date, end_date)
                 VALUES (:tournament_name, :season, :start_date, :end_date)'
            );

            $stmt->execute([
                'tournament_name' => $tournamentName,
                'season'          => $season,
                'start_date'      => $startDate,
                'end_date'        => $endDate,
            ]);

            $newTournamentId = (int) $this->db->lastInsertId();

            $link = $this->db->prepare(
                'INSERT INTO tournamentteams (tournament_id, team_id, registration_date)
                 VALUES (:tournament_id, :team_id, :registration_date)'
            );

            foreach ($teamIds as $teamId) {
                $link->execute([
                    'tournament_id'     => $newTournamentId,
                    'team_id'           => $teamId,
                    'registration_date' => $startDate,
                ]);
            }

            $this->db->commit();
        } catch (\Throwable $e) {
            $this->db->rollBack();
            throw $e;
        }
    }
}
