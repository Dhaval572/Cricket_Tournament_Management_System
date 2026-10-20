<?php
/**
 * Player statistics tab: batting and bowling leaderboards.
 */

$battingStats = $battingStats ?? [];
$bowlingStats = $bowlingStats ?? [];
?>
<div class="info-box">
    <strong>📊 Season Leaderboards:</strong>
    Batting averages, strike rates, bowling averages and economy rates are
    compiled from every recorded match scorecard.
</div>

<!-- Batting Leaderboard -->
<div class="card">
    <div class="card-header">
        <div class="card-title">🏏 Top Batting Performances</div>
    </div>
    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>Rank</th>
                    <th>Player</th>
                    <th>Team</th>
                    <th>Innings</th>
                    <th>Runs</th>
                    <th>Highest</th>
                    <th>Balls</th>
                    <th>4s / 6s</th>
                    <th>Average</th>
                    <th>Strike Rate</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($battingStats)): ?>
                    <tr><td colspan="10">No batting data yet.</td></tr>
                <?php endif; ?>
                <?php $rank = 0;
                foreach ($battingStats as $row): $rank++; ?>
                    <tr>
                        <td>#<?= $rank ?></td>
                        <td><strong><?= e($row['player_name']) ?></strong></td>
                        <td><?= e($row['team_name'] ?: 'N/A') ?></td>
                        <td><?= (int) $row['innings_batted'] ?></td>
                        <td style="color: #fbbf24; font-weight: 800; font-size: 1rem;"><?= (int) $row['total_runs'] ?></td>
                        <td><?= (int) $row['highest_score'] ?></td>
                        <td><?= (int) $row['total_balls'] ?></td>
                        <td><?= (int) $row['total_fours'] ?> / <?= (int) $row['total_sixes'] ?></td>
                        <td><?= e($row['batting_avg']) ?></td>
                        <td><span class="badge badge-t20"><?= e($row['strike_rate']) ?></span></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Bowling Leaderboard -->
<div class="card">
    <div class="card-header">
        <div class="card-title">🎯 Top Bowling Performances</div>
    </div>
    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>Rank</th>
                    <th>Player</th>
                    <th>Team</th>
                    <th>Innings</th>
                    <th>Overs</th>
                    <th>Maidens</th>
                    <th>Runs Conceded</th>
                    <th>Wickets</th>
                    <th>Bowling Avg</th>
                    <th>Economy</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($bowlingStats)): ?>
                    <tr><td colspan="10">No bowling data yet.</td></tr>
                <?php endif; ?>
                <?php $rank = 0;
                foreach ($bowlingStats as $row): $rank++; ?>
                    <tr>
                        <td>#<?= $rank ?></td>
                        <td><strong><?= e($row['player_name']) ?></strong></td>
                        <td><?= e($row['team_name'] ?: 'N/A') ?></td>
                        <td><?= (int) $row['innings_bowled'] ?></td>
                        <td><?= e($row['total_overs']) ?></td>
                        <td><?= (int) $row['total_maidens'] ?></td>
                        <td><?= (int) $row['total_runs_conceded'] ?></td>
                        <td style="color: #34d399; font-weight: 800; font-size: 1rem;"><?= (int) $row['total_wickets'] ?></td>
                        <td><?= e($row['bowling_avg']) ?></td>
                        <td><span class="badge badge-odi"><?= e($row['economy_rate']) ?></span></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
