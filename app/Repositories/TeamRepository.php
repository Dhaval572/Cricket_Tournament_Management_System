<?php

namespace App\Repositories;

use App\Core\Database;
use PDO;

/**
 * Data access for the teams table.
 */
final class TeamRepository
{
    private PDO $db;

    public function __construct(?PDO $db = null)
    {
        $this->db = $db ?? Database::connect();
    }

    /**
     * Plain team list for dropdowns and checkbox lists.
     */
    public function all(): array
    {
        $stmt = $this->db->query(
            'SELECT team_id, team_name, country
             FROM teams
             ORDER BY team_name'
        );

        return $stmt->fetchAll();
    }

    /**
     * Teams with squad size and tournament entry counts (Teams tab).
     */
    public function allWithCounts(): array
    {
        $stmt = $this->db->query(
            'SELECT t.team_id,
                    t.team_name,
                    t.country,
                    COUNT(DISTINCT tp.player_id)   AS player_count,
                    COUNT(DISTINCT tt.tournament_id) AS tournament_count
             FROM teams t
             LEFT JOIN teamplayers tp    ON t.team_id = tp.team_id
             LEFT JOIN tournamentteams tt ON t.team_id = tt.team_id
             GROUP BY t.team_id, t.team_name, t.country
             ORDER BY t.team_id'
        );

        return $stmt->fetchAll();
    }

    public function create(string $teamName, string $country): void
    {
        $stmt = $this->db->prepare(
            'INSERT INTO teams (team_name, country)
             VALUES (:team_name, :country)'
        );

        $stmt->execute([
            'team_name' => $teamName,
            'country'   => $country,
        ]);
    }
}
