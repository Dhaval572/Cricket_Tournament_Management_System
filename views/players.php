<?php
/**
 * Players tab: player list with current team, registration form.
 */

use App\Core\Csrf;

$players = $players ?? [];
$teams = $teams ?? [];
?>
<div class="layout-split">
    <div class="card">
        <div class="card-header">
            <div class="card-title">🧢 Registered Players & Team Squads</div>
        </div>
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Player Name</th>
                        <th>Current Team</th>
                        <th>Date of Birth</th>
                        <th>Batting Style</th>
                        <th>Bowling Style</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($players)): ?>
                        <tr><td colspan="6">No players registered yet.</td></tr>
                    <?php endif; ?>
                    <?php foreach ($players as $player): ?>
                        <tr>
                            <td>#<?= (int) $player['player_id'] ?></td>
                            <td><strong><?= e($player['player_name']) ?></strong></td>
                            <td>
                                <?php if (!empty($player['team_name'])): ?>
                                    <span class="badge badge-completed"><?= e($player['team_name']) ?></span>
                                <?php else: ?>
                                    <span class="badge badge-scheduled">Unassigned</span>
                                <?php endif; ?>
                            </td>
                            <td><?= e($player['date_of_birth']) ?></td>
                            <td><?= e($player['batting_style']) ?></td>
                            <td><?= e($player['bowling_style']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <div class="card-title">➕ Register New Player</div>
        </div>
        <form action="index.php" method="POST">
            <?= Csrf::field() ?>
            <input type="hidden" name="action" value="player.create">

            <div class="form-group">
                <label>Player Full Name *</label>
                <input type="text" name="player_name" class="form-control"
                       placeholder="e.g., Shubman Gill" required>
            </div>
            <div class="form-group">
                <label>Date of Birth *</label>
                <input type="date" name="date_of_birth" class="form-control"
                       value="1999-09-08" required>
            </div>
            <div class="form-group">
                <label>Batting Style *</label>
                <select name="batting_style" class="form-control" required>
                    <option value="Right-hand bat">Right-hand bat</option>
                    <option value="Left-hand bat">Left-hand bat</option>
                </select>
            </div>
            <div class="form-group">
                <label>Bowling Style</label>
                <select name="bowling_style" class="form-control">
                    <option value="None">None (Specialist Batter/WK)</option>
                    <option value="Right-arm fast">Right-arm fast</option>
                    <option value="Right-arm medium">Right-arm medium</option>
                    <option value="Right-arm offbreak">Right-arm offbreak</option>
                    <option value="Right-arm legbreak">Right-arm legbreak</option>
                    <option value="Left-arm fast">Left-arm fast</option>
                    <option value="Slow left-arm orthodox">Slow left-arm orthodox</option>
                </select>
            </div>
            <div class="form-group">
                <label>Assign to Team</label>
                <select name="team_id" class="form-control">
                    <option value="">-- Optional: Select Team --</option>
                    <?php foreach ($teams as $team): ?>
                        <option value="<?= (int) $team['team_id'] ?>">
                            <?= e($team['team_name']) ?> (<?= e($team['country']) ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit" class="btn-submit">Register Player</button>
        </form>
    </div>
</div>
