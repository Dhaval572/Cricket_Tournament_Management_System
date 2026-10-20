-- =====================================================================
-- Cricket Tournament Management System — Sample Data
-- Run AFTER schema.sql:  mysql -u root -p < sql/seed.sql
-- Explicit IDs kept so cross-table references stay readable.
-- =====================================================================

USE cricket_tournament;

SET FOREIGN_KEY_CHECKS = 0;
TRUNCATE TABLE battingscorecard;
TRUNCATE TABLE bowlingscorecard;
TRUNCATE TABLE matchofficials;
TRUNCATE TABLE matches;
TRUNCATE TABLE tournamentteams;
TRUNCATE TABLE tournaments;
TRUNCATE TABLE support_staff;
TRUNCATE TABLE teamplayers;
TRUNCATE TABLE players;
TRUNCATE TABLE teams;
TRUNCATE TABLE venues;
TRUNCATE TABLE umpires;
SET FOREIGN_KEY_CHECKS = 1;

-- ---------------------------------------------------------------------
-- Teams (10)
-- ---------------------------------------------------------------------
INSERT INTO teams (team_id, team_name, country) VALUES
    (1,  'India',          'India'),
    (2,  'Australia',      'Australia'),
    (3,  'England',        'England'),
    (4,  'South Africa',   'South Africa'),
    (5,  'New Zealand',    'New Zealand'),
    (6,  'Pakistan',       'Pakistan'),
    (7,  'Sri Lanka',      'Sri Lanka'),
    (8,  'West Indies',    'West Indies'),
    (9,  'Bangladesh',     'Bangladesh'),
    (10, 'Afghanistan',    'Afghanistan');

-- ---------------------------------------------------------------------
-- Players (3 per team, IDs 3n-2 .. 3n follow team order)
-- ---------------------------------------------------------------------
INSERT INTO players (player_id, player_name, date_of_birth, batting_style, bowling_style) VALUES
    (1,  'Rohit Sharma',      '1987-04-30', 'Right-hand bat', 'Right-arm offbreak'),
    (2,  'Virat Kohli',       '1988-11-05', 'Right-hand bat', 'Right-arm medium'),
    (3,  'Jasprit Bumrah',    '1993-12-06', 'Right-hand bat', 'Right-arm fast'),
    (4,  'Pat Cummins',       '1993-05-08', 'Right-hand bat', 'Right-arm fast'),
    (5,  'Mitchell Marsh',    '1991-10-20', 'Right-hand bat', 'Right-arm medium'),
    (6,  'Glenn Maxwell',     '1988-10-14', 'Right-hand bat', 'Right-arm offbreak'),
    (7,  'Joe Root',          '1990-12-30', 'Right-hand bat', 'Right-arm offbreak'),
    (8,  'Ben Stokes',        '1991-06-04', 'Left-hand bat',  'Right-arm medium'),
    (9,  'Jos Buttler',       '1990-09-08', 'Right-hand bat', 'None'),
    (10, 'Aiden Markram',     '1994-10-04', 'Right-hand bat', 'Right-arm offbreak'),
    (11, 'Kagiso Rabada',     '1995-05-25', 'Right-hand bat', 'Right-arm fast'),
    (12, 'Quinton de Kock',   '1992-12-27', 'Left-hand bat',  'None'),
    (13, 'Kane Williamson',   '1990-08-08', 'Right-hand bat', 'Right-arm offbreak'),
    (14, 'Trent Boult',       '1989-07-22', 'Right-hand bat', 'Left-arm fast'),
    (15, 'Devon Conway',      '1991-07-08', 'Left-hand bat',  'Right-arm medium'),
    (16, 'Babar Azam',        '1994-10-15', 'Right-hand bat', 'Right-arm offbreak'),
    (17, 'Shaheen Afridi',    '2000-04-06', 'Left-hand bat',  'Left-arm fast'),
    (18, 'Mohammad Rizwan',   '1992-06-01', 'Right-hand bat', 'None'),
    (19, 'Kusal Mendis',      '1995-02-13', 'Right-hand bat', 'Right-arm legbreak'),
    (20, 'Wanindu Hasaranga', '1997-12-29', 'Right-hand bat', 'Right-arm legbreak'),
    (21, 'Pathum Nissanka',   '1998-02-17', 'Right-hand bat', 'None'),
    (22, 'Shai Hope',         '1993-11-10', 'Right-hand bat', 'None'),
    (23, 'Alzarri Joseph',    '1996-11-01', 'Right-hand bat', 'Right-arm fast'),
    (24, 'Nicholas Pooran',   '1995-10-02', 'Left-hand bat',  'None'),
    (25, 'Shakib Al Hasan',   '1987-03-24', 'Left-hand bat',  'Slow left-arm orthodox'),
    (26, 'Liton Das',         '1994-10-13', 'Right-hand bat', 'None'),
    (27, 'Mustafizur Rahman', '1995-09-06', 'Left-hand bat',  'Left-arm fast-medium'),
    (28, 'Rahmanullah Gurbaz','2002-02-28', 'Right-hand bat', 'None'),
    (29, 'Rashid Khan',       '1998-09-20', 'Right-hand bat', 'Right-arm legbreak'),
    (30, 'Ibrahim Zadran',    '2001-12-12', 'Right-hand bat', 'Right-arm medium');

-- Current squad membership (all open contracts)
INSERT INTO teamplayers (team_id, player_id, start_date, end_date) VALUES
    (1, 1,  '2024-01-01', NULL), (1, 2,  '2024-01-01', NULL), (1, 3,  '2024-01-01', NULL),
    (2, 4,  '2024-01-01', NULL), (2, 5,  '2024-01-01', NULL), (2, 6,  '2024-01-01', NULL),
    (3, 7,  '2024-01-01', NULL), (3, 8,  '2024-01-01', NULL), (3, 9,  '2024-01-01', NULL),
    (4, 10, '2024-01-01', NULL), (4, 11, '2024-01-01', NULL), (4, 12, '2024-01-01', NULL),
    (5, 13, '2024-01-01', NULL), (5, 14, '2024-01-01', NULL), (5, 15, '2024-01-01', NULL),
    (6, 16, '2024-01-01', NULL), (6, 17, '2024-01-01', NULL), (6, 18, '2024-01-01', NULL),
    (7, 19, '2024-01-01', NULL), (7, 20, '2024-01-01', NULL), (7, 21, '2024-01-01', NULL),
    (8, 22, '2024-01-01', NULL), (8, 23, '2024-01-01', NULL), (8, 24, '2024-01-01', NULL),
    (9, 25, '2024-01-01', NULL), (9, 26, '2024-01-01', NULL), (9, 27, '2024-01-01', NULL),
    (10, 28, '2024-01-01', NULL), (10, 29, '2024-01-01', NULL), (10, 30, '2024-01-01', NULL);

-- ---------------------------------------------------------------------
-- Tournaments (3)
-- ---------------------------------------------------------------------
INSERT INTO tournaments (tournament_id, tournament_name, season, start_date, end_date) VALUES
    (1, 'World T20 Championship', '2026', '2026-03-01', '2026-03-30'),
    (2, 'ODI Champions Cup',      '2025', '2025-09-10', '2025-09-28'),
    (3, 'Ashes Test Series',      '2026', '2026-07-01', '2026-08-05');

INSERT INTO tournamentteams (tournament_id, team_id, registration_date) VALUES
    (1, 1, '2026-03-01'), (1, 2, '2026-03-01'), (1, 3, '2026-03-01'), (1, 4, '2026-03-01'),
    (2, 1, '2025-09-10'), (2, 2, '2025-09-10'), (2, 5, '2025-09-10'), (2, 6, '2025-09-10'),
    (3, 2, '2026-07-01'), (3, 3, '2026-07-01');

-- ---------------------------------------------------------------------
-- Venues (6) and Umpires (6)
-- ---------------------------------------------------------------------
INSERT INTO venues (venue_id, stadium_name, city) VALUES
    (1, 'Wankhede Stadium',         'Mumbai'),
    (2, 'Melbourne Cricket Ground', 'Melbourne'),
    (3, "Lord's",                   'London'),
    (4, 'Newlands',                 'Cape Town'),
    (5, 'Eden Gardens',             'Kolkata'),
    (6, 'Gaddafi Stadium',          'Lahore');

INSERT INTO umpires (umpire_id, umpire_name) VALUES
    (1, 'Richard Illingworth'),
    (2, 'Marais Erasmus'),
    (3, 'Nitin Menon'),
    (4, 'Chris Gaffaney'),
    (5, 'Rod Tucker'),
    (6, 'Paul Reiffel');

-- ---------------------------------------------------------------------
-- Matches (12: 8 completed, 4 scheduled)
-- ---------------------------------------------------------------------
INSERT INTO matches
    (match_id, tournament_id, team1_id, team2_id, venue_id, format, match_date,
     match_status, winner_team_id, result_type, result_summary)
VALUES
    (1,  1, 1, 2, 1, 'T20',   '2026-03-05', 'Completed', 1,    'WIN',       'India won by 24 runs'),
    (2,  1, 3, 4, 3, 'T20',   '2026-03-07', 'Completed', 3,    'WIN',       'England won by 6 wickets'),
    (3,  1, 1, 3, 2, 'T20',   '2026-03-10', 'Completed', NULL, 'TIE',       'Match tied'),
    (4,  1, 2, 4, 4, 'T20',   '2026-03-12', 'Completed', 4,    'WIN',       'South Africa won by 8 runs'),
    (5,  1, 1, 4, 5, 'T20',   '2026-03-15', 'Scheduled', NULL, NULL,        'Upcoming group match'),
    (6,  1, 2, 3, 2, 'T20',   '2026-03-18', 'Scheduled', NULL, NULL,        'Upcoming group match'),
    (7,  2, 1, 2, 5, 'ODI',   '2025-09-12', 'Completed', 2,    'WIN',       'Australia won by 5 wickets'),
    (8,  2, 5, 6, 6, 'ODI',   '2025-09-15', 'Completed', NULL, 'NO_RESULT', 'No result (rain)'),
    (9,  2, 1, 5, 1, 'ODI',   '2025-09-18', 'Completed', 1,    'WIN',       'India won by 42 runs'),
    (10, 2, 2, 6, 2, 'ODI',   '2025-09-22', 'Scheduled', NULL, NULL,        'Upcoming ODI'),
    (11, 3, 2, 3, 2, 'Test',  '2026-07-10', 'Completed', NULL, 'DRAW',      'Match drawn'),
    (12, 3, 3, 2, 3, 'Test',  '2026-07-24', 'Scheduled', NULL, NULL,        'Upcoming Test');

-- ---------------------------------------------------------------------
-- Match officials (assigned to completed matches)
-- ---------------------------------------------------------------------
INSERT INTO matchofficials (match_id, umpire_id, official_role) VALUES
    (1,  1, 'Field Umpire'), (1,  3, 'Field Umpire'), (1,  6, 'Match Referee'),
    (2,  2, 'Field Umpire'), (2,  4, 'Field Umpire'), (2,  5, 'Match Referee'),
    (3,  1, 'Field Umpire'), (3,  5, 'Field Umpire'), (3,  6, 'Match Referee'),
    (4,  2, 'Field Umpire'), (4,  4, 'Field Umpire'), (4,  5, 'Match Referee'),
    (7,  3, 'Field Umpire'), (7,  5, 'Field Umpire'), (7,  6, 'Match Referee'),
    (8,  1, 'Field Umpire'), (8,  4, 'Field Umpire'), (8,  2, 'Match Referee'),
    (9,  3, 'Field Umpire'), (9,  2, 'Field Umpire'), (9,  6, 'Match Referee'),
    (11, 1, 'Field Umpire'), (11, 2, 'Field Umpire'), (11, 5, 'Match Referee');

-- ---------------------------------------------------------------------
-- Batting scorecards (one row per batter per completed match)
-- ---------------------------------------------------------------------
INSERT INTO battingscorecard
    (match_id, player_id, runs, balls_faced, fours, sixes, dismissal)
VALUES
    -- Match 1: India vs Australia (T20)
    (1, 1, 72, 48, 8, 2, 'c Maxwell b Cummins'),
    (1, 2, 54, 39, 5, 1, 'Not Out'),
    (1, 4, 33, 24, 3, 1, 'b Bumrah'),
    (1, 5, 21, 17, 2, 0, 'run out (Rohit Sharma)'),
    -- Match 2: England vs South Africa (T20)
    (2, 7, 68, 52, 7, 2, 'c de Kock b Rabada'),
    (2, 8, 41, 25, 3, 2, 'Not Out'),
    (2, 11, 25, 18, 2, 1, 'b Stokes'),
    (2, 12, 44, 36, 5, 1, 'c Buttler b Root'),
    -- Match 3: India vs England (T20, tied)
    (3, 1, 39, 31, 4, 1, 'c Stokes b Root'),
    (3, 2, 58, 42, 6, 1, 'Not Out'),
    (3, 7, 58, 45, 6, 1, 'b Bumrah'),
    (3, 8, 12, 9, 1, 0, 'c Kohli b Bumrah'),
    -- Match 4: Australia vs South Africa (T20)
    (4, 5, 47, 35, 5, 1, 'c Markram b Rabada'),
    (4, 6, 29, 21, 2, 2, 'b Rabada'),
    (4, 10, 55, 44, 6, 1, 'c Maxwell b Cummins'),
    (4, 12, 38, 28, 4, 1, 'Not Out'),
    -- Match 7: India vs Australia (ODI)
    (7, 1, 88, 95, 10, 2, 'c Carey b Cummins'),
    (7, 2, 76, 88, 7, 1, 'Not Out'),
    (7, 5, 64, 71, 6, 1, 'b Bumrah'),
    (7, 6, 35, 27, 2, 2, 'c Kohli b Bumrah'),
    -- Match 8: New Zealand vs Pakistan (ODI, no result)
    (8, 13, 45, 52, 4, 0, 'c Rizwan b Shaheen'),
    (8, 16, 38, 44, 4, 0, 'Not Out'),
    -- Match 9: India vs New Zealand (ODI)
    (9, 2, 92, 101, 9, 1, 'c Conway b Boult'),
    (9, 1, 33, 41, 4, 0, 'b Boult'),
    (9, 15, 57, 63, 6, 0, 'b Bumrah'),
    (9, 13, 24, 33, 2, 0, 'c Kohli b Bumrah'),
    -- Match 11: Australia vs England (Test, drawn)
    (11, 4, 55, 88, 6, 0, 'c Buttler b Stokes'),
    (11, 5, 31, 64, 3, 0, 'b Root'),
    (11, 7, 104, 189, 12, 1, 'c Maxwell b Cummins'),
    (11, 8, 67, 121, 7, 1, 'Not Out');

-- ---------------------------------------------------------------------
-- Bowling scorecards (one row per bowler per completed match)
-- ---------------------------------------------------------------------
INSERT INTO bowlingscorecard
    (match_id, player_id, overs, maidens, runs_conceded, wickets)
VALUES
    -- Match 1
    (1, 3, 4.0, 0, 22, 3),
    (1, 4, 4.0, 0, 28, 2),
    -- Match 2
    (2, 11, 4.0, 0, 30, 2),
    (2, 8, 4.0, 1, 25, 1),
    -- Match 3
    (3, 3, 4.0, 0, 26, 2),
    (3, 7, 4.0, 0, 31, 1),
    -- Match 4
    (4, 11, 4.0, 0, 27, 3),
    (4, 4, 4.0, 0, 33, 1),
    -- Match 7 (ODI, 10 over spells)
    (7, 3, 10.0, 1, 45, 2),
    (7, 4, 10.0, 0, 52, 1),
    -- Match 8 (rain affected)
    (8, 14, 7.0, 2, 24, 1),
    (8, 17, 7.0, 1, 31, 1),
    -- Match 9
    (9, 3, 10.0, 1, 38, 3),
    (9, 14, 10.0, 0, 44, 2),
    -- Match 11 (Test spells)
    (11, 4, 30.0, 5, 95, 3),
    (11, 8, 28.0, 4, 90, 2),
    (11, 7, 30.0, 6, 85, 1),
    (11, 5, 20.0, 3, 70, 1);

-- ---------------------------------------------------------------------
-- Support staff
-- ---------------------------------------------------------------------
INSERT INTO support_staff (staff_id, staff_name, role, country, team_id) VALUES
    (1, 'Rahul Dravid',            'Head Coach',                        'India',         1),
    (2, 'Ricky Ponting',           'Head Coach',                        'Australia',     2),
    (3, 'Brendon McCullum',        'Head Coach',                        'England',       3),
    (4, 'Gary Kirsten',            'Batting Coach',                     'South Africa',  4),
    (5, 'Daniel Vettori',          'Bowling Coach',                     'New Zealand',   5),
    (6, 'Saqlain Mushtaq',         'Bowling Coach',                     'Pakistan',      6),
    (7, 'Andrew Leipus',           'Lead Physiotherapist',              'England',       NULL),
    (8, 'Simon Katich',            'Performance Video Analyst',         'Australia',     NULL);
