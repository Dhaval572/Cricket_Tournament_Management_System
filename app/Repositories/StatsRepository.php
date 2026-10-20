<?php

namespace App\Repositories;

use App\Core\Database;
use PDO;

/**
 * Aggregated read-only queries: dashboard counts, points tables,
 * batting and bowling leaderboards.
 */
final class StatsRepository
{
    private PDO $db;

    public function __construct(?PDO $db = null)
    {
        $this->db = $db ?? Database::connect();
    }

    /**
     * Row counts for the five dashboard cards.
     */
    public function counts(): array
    {
        $stmt = $this->db->query(
            'SELECT (SELECT COUNT(*) FROM tournaments) AS total_tournaments,
                    (SELECT COUNT(*) FROM teams)       AS total_teams,
                    (SELECT COUNT(*) FROM players)     AS total_players,
                    (SELECT COUNT(*) FROM matches)     AS total_matches,
                    (SELECT COUNT(*) FROM support_staff) AS total_staff'
        );

        return $stmt->fetch() ?: [];
    }

    /**
     * Points table per tournament, computed from completed matches only.
     *
     * Points system: WIN = 2, TIE / DRAW / NO_RESULT = 1, LOSS = 0.
     * Scheduled matches never count toward played or points.
     */
    public function standings(): array
    {
        $stmt = $this->db->query(
            'SELECT tr.tournament_id,
                    tr.tournament_name,
                    tr.season,
                    t.team_id,
                    t.team_name,
                    COUNT(m.match_id) AS played,
                    SUM(CASE
                        WHEN m.result_type = \'WIN\' AND m.winner_team_id = t.team_id THEN 1
                        ELSE 0
                    END) AS won,
                    SUM(CASE
                        WHEN m.result_type = \'WIN\'
                         AND m.winner_team_id IS NOT NULL
                         AND m.winner_team_id <> t.team_id THEN 1
                        ELSE 0
                    END) AS lost,
                    SUM(CASE WHEN m.result_type = \'TIE\' THEN 1 ELSE 0 END) AS tied,
                    SUM(CASE
                        WHEN m.result_type IN (\'DRAW\', \'NO_RESULT\') THEN 1
                        ELSE 0
                    END) AS draw_nr,
                    SUM(CASE
                        WHEN m.result_type = \'WIN\' AND m.winner_team_id = t.team_id THEN 2
                        WHEN m.result_type IN (\'TIE\', \'DRAW\', \'NO_RESULT\') THEN 1
                        ELSE 0
                    END) AS points
             FROM tournamentteams tt
             JOIN tournaments tr ON tt.tournament_id = tr.tournament_id
             JOIN teams t        ON tt.team_id = t.team_id
             LEFT JOIN matches m
                    ON m.tournament_id = tr.tournament_id
                   AND m.match_status = \'Completed\'
                   AND (m.team1_id = t.team_id OR m.team2_id = t.team_id)
             GROUP BY tr.tournament_id, tr.tournament_name, tr.season,
                      t.team_id, t.team_name
             ORDER BY tr.tournament_id ASC, points DESC, won DESC, t.team_name ASC'
        );

        return $stmt->fetchAll();
    }

    /**
     * Standings grouped per tournament for rendering one card each.
     */
    public function standingsGroupedByTournament(): array
    {
        $groups = [];

        foreach ($this->standings() as $row) {
            $key = $row['tournament_name'] . ' (' . $row['season'] . ')';
            $groups[$key][] = $row;
        }

        return $groups;
    }

    /**
     * Batting leaderboard aggregated from the batting scorecard.
     */
    public function battingLeaders(): array
    {
        $stmt = $this->db->query(
            'SELECT p.player_id,
                    p.player_name,
                    t.team_name,
                    COUNT(bs.innings_id) AS innings_batted,
                    SUM(bs.runs)         AS total_runs,
                    MAX(bs.runs)         AS highest_score,
                    SUM(bs.balls_faced)  AS total_balls,
                    SUM(bs.fours)        AS total_fours,
                    SUM(bs.sixes)        AS total_sixes,
                    ROUND(
                        CASE
                            WHEN SUM(CASE WHEN bs.dismissal <> \'Not Out\' THEN 1 ELSE 0 END) = 0
                                THEN SUM(bs.runs)
                            ELSE SUM(bs.runs) /
                                 SUM(CASE WHEN bs.dismissal <> \'Not Out\' THEN 1 ELSE 0 END)
                        END, 2
                    ) AS batting_avg,
                    ROUND(
                        CASE
                            WHEN SUM(bs.balls_faced) > 0
                                THEN (SUM(bs.runs) * 100.0) / SUM(bs.balls_faced)
                            ELSE 0.0
                        END, 2
                    ) AS strike_rate
             FROM battingscorecard bs
             JOIN players p ON bs.player_id = p.player_id
             LEFT JOIN teamplayers tp
                    ON p.player_id = tp.player_id
                   AND tp.end_date IS NULL
             LEFT JOIN teams t ON tp.team_id = t.team_id
             GROUP BY p.player_id, p.player_name, t.team_name
             ORDER BY total_runs DESC, strike_rate DESC'
        );

        return $stmt->fetchAll();
    }

    /**
     * Bowling leaderboard aggregated from the bowling scorecard.
     */
    public function bowlingLeaders(): array
    {
        $stmt = $this->db->query(
            'SELECT p.player_id,
                    p.player_name,
                    t.team_name,
                    COUNT(bw.innings_id)       AS innings_bowled,
                    SUM(bw.overs)             AS total_overs,
                    SUM(bw.maidens)           AS total_maidens,
                    SUM(bw.runs_conceded)     AS total_runs_conceded,
                    SUM(bw.wickets)           AS total_wickets,
                    ROUND(
                        CASE
                            WHEN SUM(bw.wickets) > 0
                                THEN SUM(bw.runs_conceded) / SUM(bw.wickets)
                            ELSE 0.0
                        END, 2
                    ) AS bowling_avg,
                    ROUND(
                        CASE
                            WHEN SUM(bw.overs) > 0
                                THEN SUM(bw.runs_conceded) / SUM(bw.overs)
                            ELSE 0.0
                        END, 2
                    ) AS economy_rate
             FROM bowlingscorecard bw
             JOIN players p ON bw.player_id = p.player_id
             LEFT JOIN teamplayers tp
                    ON p.player_id = tp.player_id
                   AND tp.end_date IS NULL
             LEFT JOIN teams t ON tp.team_id = t.team_id
             GROUP BY p.player_id, p.player_name, t.team_name
             ORDER BY total_wickets DESC, economy_rate ASC'
        );

        return $stmt->fetchAll();
    }
}
