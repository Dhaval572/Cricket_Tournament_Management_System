<?php

namespace App\Repositories;

use App\Core\Database;
use PDO;

/**
 * Data access for players and their squad membership.
 */
final class PlayerRepository
{
    private PDO $db;

    public function __construct(?PDO $db = null)
    {
        $this->db = $db ?? Database::connect();
    }

    /**
     * All players with the team they currently belong to
     * (open contract: end_date IS NULL).
     */
    public function allWithCurrentTeam(): array
    {
        $stmt = $this->db->query(
            'SELECT p.player_id,
                    p.player_name,
                    p.date_of_birth,
                    p.batting_style,
                    p.bowling_style,
                    t.team_name,
                    t.country AS team_country
             FROM players p
             LEFT JOIN teamplayers tp
                    ON p.player_id = tp.player_id
                   AND tp.end_date IS NULL
             LEFT JOIN teams t ON tp.team_id = t.team_id
             ORDER BY p.player_id'
        );

        return $stmt->fetchAll();
    }

    /**
     * Register a player and optionally assign the player to a team.
     * Both statements run inside one transaction.
     */
    public function create(
        string $playerName,
        string $dateOfBirth,
        string $battingStyle,
        string $bowlingStyle,
        ?int $teamId,
        string $startDate
    ): void {
        $this->db->beginTransaction();

        try {
            $stmt = $this->db->prepare(
                'INSERT INTO players (player_name, date_of_birth, batting_style, bowling_style)
                 VALUES (:player_name, :date_of_birth, :batting_style, :bowling_style)'
            );

            $stmt->execute([
                'player_name'   => $playerName,
                'date_of_birth' => $dateOfBirth,
                'batting_style' => $battingStyle,
                'bowling_style' => $bowlingStyle,
            ]);

            $newPlayerId = (int) $this->db->lastInsertId();

            if ($teamId !== null) {
                $link = $this->db->prepare(
                    'INSERT INTO teamplayers (team_id, player_id, start_date, end_date)
                     VALUES (:team_id, :player_id, :start_date, NULL)'
                );

                $link->execute([
                    'team_id'    => $teamId,
                    'player_id'  => $newPlayerId,
                    'start_date' => $startDate,
                ]);
            }

            $this->db->commit();
        } catch (\Throwable $e) {
            $this->db->rollBack();
            throw $e;
        }
    }
}
