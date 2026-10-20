-- =====================================================================
-- Cricket Tournament Management System — Database Schema
-- Engine: MySQL 8.x  |  Charset: utf8mb4
-- Run:    mysql -u root -p < sql/schema.sql
-- =====================================================================

CREATE DATABASE IF NOT EXISTS cricket_tournament
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE cricket_tournament;

-- ---------------------------------------------------------------------
-- Teams
-- ---------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS teams (
    team_id     INT UNSIGNED NOT NULL AUTO_INCREMENT,
    team_name   VARCHAR(100) NOT NULL,
    country     VARCHAR(100) NOT NULL,
    PRIMARY KEY (team_id),
    UNIQUE KEY uq_teams_name (team_name)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- Players
-- ---------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS players (
    player_id     INT UNSIGNED NOT NULL AUTO_INCREMENT,
    player_name   VARCHAR(120) NOT NULL,
    date_of_birth DATE         NOT NULL,
    batting_style ENUM('Right-hand bat', 'Left-hand bat') NOT NULL,
    bowling_style VARCHAR(60)  NOT NULL DEFAULT 'None',
    PRIMARY KEY (player_id),
    KEY idx_players_name (player_name)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- Player <-> Team membership (open contract while end_date IS NULL)
-- ---------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS teamplayers (
    team_id    INT UNSIGNED NOT NULL,
    player_id  INT UNSIGNED NOT NULL,
    start_date DATE         NOT NULL,
    end_date   DATE         NULL,
    PRIMARY KEY (team_id, player_id),
    KEY idx_teamplayers_player (player_id),
    CONSTRAINT fk_teamplayers_team
        FOREIGN KEY (team_id) REFERENCES teams (team_id)
        ON DELETE CASCADE,
    CONSTRAINT fk_teamplayers_player
        FOREIGN KEY (player_id) REFERENCES players (player_id)
        ON DELETE CASCADE
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- Tournaments
-- ---------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS tournaments (
    tournament_id   INT UNSIGNED NOT NULL AUTO_INCREMENT,
    tournament_name VARCHAR(150) NOT NULL,
    season          VARCHAR(20)  NOT NULL,
    start_date      DATE         NOT NULL,
    end_date        DATE         NOT NULL,
    PRIMARY KEY (tournament_id),
    CONSTRAINT chk_tournaments_dates CHECK (end_date >= start_date)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- Tournament <-> Team registration
-- ---------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS tournamentteams (
    tournament_id     INT UNSIGNED NOT NULL,
    team_id           INT UNSIGNED NOT NULL,
    registration_date DATE         NOT NULL,
    PRIMARY KEY (tournament_id, team_id),
    KEY idx_tournamentteams_team (team_id),
    CONSTRAINT fk_tournamentteams_tournament
        FOREIGN KEY (tournament_id) REFERENCES tournaments (tournament_id)
        ON DELETE CASCADE,
    CONSTRAINT fk_tournamentteams_team
        FOREIGN KEY (team_id) REFERENCES teams (team_id)
        ON DELETE CASCADE
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- Venues
-- ---------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS venues (
    venue_id      INT UNSIGNED NOT NULL AUTO_INCREMENT,
    stadium_name  VARCHAR(120) NOT NULL,
    city          VARCHAR(80)  NOT NULL,
    PRIMARY KEY (venue_id)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- Umpires
-- ---------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS umpires (
    umpire_id    INT UNSIGNED NOT NULL AUTO_INCREMENT,
    umpire_name  VARCHAR(120) NOT NULL,
    PRIMARY KEY (umpire_id)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- Matches
-- ---------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS matches (
    match_id       INT UNSIGNED NOT NULL AUTO_INCREMENT,
    tournament_id  INT UNSIGNED NOT NULL,
    team1_id       INT UNSIGNED NOT NULL,
    team2_id       INT UNSIGNED NOT NULL,
    venue_id       INT UNSIGNED NOT NULL,
    format         ENUM('T20', 'ODI', 'Test') NOT NULL,
    match_date     DATE         NOT NULL,
    match_status   ENUM('Scheduled', 'Completed') NOT NULL DEFAULT 'Scheduled',
    winner_team_id INT UNSIGNED NULL,
    result_type    ENUM('WIN', 'TIE', 'DRAW', 'NO_RESULT') NULL,
    result_summary VARCHAR(255) NULL,
    PRIMARY KEY (match_id),
    KEY idx_matches_tournament (tournament_id),
    KEY idx_matches_date (match_date),
    KEY idx_matches_format (format),
    CONSTRAINT chk_matches_distinct_teams CHECK (team1_id <> team2_id),
    CONSTRAINT chk_matches_winner_on_win
        CHECK (result_type <> 'WIN' OR winner_team_id IS NOT NULL),
    CONSTRAINT chk_matches_result_when_completed
        CHECK (match_status = 'Scheduled' OR result_type IS NOT NULL),
    CONSTRAINT fk_matches_tournament
        FOREIGN KEY (tournament_id) REFERENCES tournaments (tournament_id),
    CONSTRAINT fk_matches_team1
        FOREIGN KEY (team1_id) REFERENCES teams (team_id),
    CONSTRAINT fk_matches_team2
        FOREIGN KEY (team2_id) REFERENCES teams (team_id),
    CONSTRAINT fk_matches_venue
        FOREIGN KEY (venue_id) REFERENCES venues (venue_id),
    CONSTRAINT fk_matches_winner
        FOREIGN KEY (winner_team_id) REFERENCES teams (team_id)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- Match officials (umpires + referee per match)
-- ---------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS matchofficials (
    match_id      INT UNSIGNED NOT NULL,
    umpire_id     INT UNSIGNED NOT NULL,
    official_role ENUM('Field Umpire', 'Third Umpire', 'Fourth Umpire', 'Match Referee') NOT NULL,
    PRIMARY KEY (match_id, umpire_id, official_role),
    CONSTRAINT fk_matchofficials_match
        FOREIGN KEY (match_id) REFERENCES matches (match_id)
        ON DELETE CASCADE,
    CONSTRAINT fk_matchofficials_umpire
        FOREIGN KEY (umpire_id) REFERENCES umpires (umpire_id)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- Support staff
-- ---------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS support_staff (
    staff_id   INT UNSIGNED NOT NULL AUTO_INCREMENT,
    staff_name VARCHAR(120) NOT NULL,
    role       VARCHAR(80)  NOT NULL,
    country    VARCHAR(80)  NOT NULL,
    team_id    INT UNSIGNED NULL,
    PRIMARY KEY (staff_id),
    KEY idx_staff_team (team_id),
    CONSTRAINT fk_support_staff_team
        FOREIGN KEY (team_id) REFERENCES teams (team_id)
        ON DELETE SET NULL
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- Batting scorecard (one row per batter per match)
-- ---------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS battingscorecard (
    innings_id   INT UNSIGNED NOT NULL AUTO_INCREMENT,
    match_id     INT UNSIGNED NOT NULL,
    player_id    INT UNSIGNED NOT NULL,
    runs         INT UNSIGNED NOT NULL DEFAULT 0,
    balls_faced  INT UNSIGNED NOT NULL DEFAULT 0,
    fours        INT UNSIGNED NOT NULL DEFAULT 0,
    sixes        INT UNSIGNED NOT NULL DEFAULT 0,
    dismissal    VARCHAR(60)  NOT NULL DEFAULT 'Not Out',
    PRIMARY KEY (innings_id),
    UNIQUE KEY uq_batting_match_player (match_id, player_id),
    KEY idx_batting_player (player_id),
    CONSTRAINT fk_batting_match
        FOREIGN KEY (match_id) REFERENCES matches (match_id)
        ON DELETE CASCADE,
    CONSTRAINT fk_batting_player
        FOREIGN KEY (player_id) REFERENCES players (player_id)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- Bowling scorecard (one row per bowler per match)
-- ---------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS bowlingscorecard (
    innings_id      INT UNSIGNED NOT NULL AUTO_INCREMENT,
    match_id        INT UNSIGNED NOT NULL,
    player_id       INT UNSIGNED NOT NULL,
    overs           DECIMAL(4,1) NOT NULL DEFAULT 0.0,
    maidens         INT UNSIGNED NOT NULL DEFAULT 0,
    runs_conceded   INT UNSIGNED NOT NULL DEFAULT 0,
    wickets         INT UNSIGNED NOT NULL DEFAULT 0,
    PRIMARY KEY (innings_id),
    UNIQUE KEY uq_bowling_match_player (match_id, player_id),
    KEY idx_bowling_player (player_id),
    CONSTRAINT chk_bowling_overs CHECK (overs >= 0),
    CONSTRAINT fk_bowling_match
        FOREIGN KEY (match_id) REFERENCES matches (match_id)
        ON DELETE CASCADE,
    CONSTRAINT fk_bowling_player
        FOREIGN KEY (player_id) REFERENCES players (player_id)
) ENGINE=InnoDB;
