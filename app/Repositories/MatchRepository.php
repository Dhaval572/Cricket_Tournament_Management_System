<?php

namespace App\Repositories;

use App\Core\Database;
use PDO;

/**
 * Data access for matches, venues and umpires.
 */
final class MatchRepository
{
    private PDO $db;

    public function __construct(?PDO $db = null)
    {
        $this->db = $db ?? Database::connect();
    }

    /**
     * Matches with tournament, teams, venue and match officials.
     * Filter by format: T20, ODI, Test or null for all formats.
     */
    public function all(?string $format = null): array
    {
        $sql = 'SELECT m.match_id,
                       m.format,
                       m.match_date,
                       m.match_status,
                       m.result_type,
                       m.result_summary,
                       tr.tournament_name,
                       t1.team_name AS team1_name,
                       t2.team_name AS team2_name,
                       tw.team_name AS winner_name,
                       v.stadium_name,
                       v.city,
                       GROUP_CONCAT(
                           CONCAT(u.umpire_name, \' (\', mo.official_role, \')\')
                           ORDER BY mo.official_role, u.umpire_name
                           SEPARATOR \', \'
                       ) AS officials
                FROM matches m
                JOIN tournaments tr ON m.tournament_id = tr.tournament_id
                JOIN teams t1       ON m.team1_id = t1.team_id
                JOIN teams t2       ON m.team2_id = t2.team_id
                JOIN venues v       ON m.venue_id = v.venue_id
                LEFT JOIN teams tw          ON m.winner_team_id = tw.team_id
                LEFT JOIN matchofficials mo ON m.match_id = mo.match_id
                LEFT JOIN umpires u         ON mo.umpire_id = u.umpire_id';

        $params = [];

        if ($format !== null) {
            $sql .= ' WHERE m.format = :format';
            $params['format'] = $format;
        }

        $sql .= ' GROUP BY m.match_id, m.format, m.match_date, m.match_status,
                           m.result_type, m.result_summary, tr.tournament_name,
                           t1.team_name, t2.team_name, tw.team_name,
                           v.stadium_name, v.city
                  ORDER BY m.match_date DESC, m.match_id DESC';

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll();
    }

    /**
     * Recently completed matches for the dashboard.
     */
    public function recentCompleted(int $limit): array
    {
        $stmt = $this->db->prepare(
            'SELECT m.match_date,
                    m.format,
                    m.result_summary,
                    tr.tournament_name,
                    t1.team_name AS team1_name,
                    t2.team_name AS team2_name
             FROM matches m
             JOIN tournaments tr ON m.tournament_id = tr.tournament_id
             JOIN teams t1 ON m.team1_id = t1.team_id
             JOIN teams t2 ON m.team2_id = t2.team_id
             WHERE m.match_status = \'Completed\'
             ORDER BY m.match_date DESC, m.match_id DESC
             LIMIT :row_limit'
        );
        $stmt->bindValue(':row_limit', $limit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    /**
     * Upcoming scheduled matches for the dashboard.
     */
    public function upcoming(int $limit): array
    {
        $stmt = $this->db->prepare(
            'SELECT m.match_date,
                    m.format,
                    tr.tournament_name,
                    t1.team_name AS team1_name,
                    t2.team_name AS team2_name,
                    v.stadium_name,
                    v.city
             FROM matches m
             JOIN tournaments tr ON m.tournament_id = tr.tournament_id
             JOIN teams t1 ON m.team1_id = t1.team_id
             JOIN teams t2 ON m.team2_id = t2.team_id
             JOIN venues v ON m.venue_id = v.venue_id
             WHERE m.match_status = \'Scheduled\'
             ORDER BY m.match_date ASC, m.match_id ASC
             LIMIT :row_limit'
        );
        $stmt->bindValue(':row_limit', $limit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function create(array $data): void
    {
        $stmt = $this->db->prepare(
            'INSERT INTO matches
                (tournament_id, team1_id, team2_id, venue_id, format,
                 match_date, match_status, winner_team_id, result_type, result_summary)
             VALUES
                (:tournament_id, :team1_id, :team2_id, :venue_id, :format,
                 :match_date, :match_status, :winner_team_id, :result_type, :result_summary)'
        );

        $stmt->execute($data);
    }

    public function venues(): array
    {
        $stmt = $this->db->query(
            'SELECT venue_id, stadium_name, city
             FROM venues
             ORDER BY stadium_name'
        );

        return $stmt->fetchAll();
    }

    public function umpires(): array
    {
        $stmt = $this->db->query(
            'SELECT umpire_id, umpire_name
             FROM umpires
             ORDER BY umpire_name'
        );

        return $stmt->fetchAll();
    }
}
