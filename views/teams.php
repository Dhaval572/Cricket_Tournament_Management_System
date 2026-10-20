<?php
/**
 * Teams tab: team list with counts, add-team form.
 */

use App\Core\Csrf;

$teams = $teams ?? [];
?>
<div class="layout-split">
    <div class="card">
        <div class="card-header">
            <div class="card-title">🛡️ Registered Teams</div>
        </div>
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>Team ID</th>
                        <th>Team Name</th>
                        <th>Country</th>
                        <th>Squad Size</th>
                        <th>Tournaments Entered</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($teams)): ?>
                        <tr><td colspan="5">No teams registered yet.</td></tr>
                    <?php endif; ?>
                    <?php foreach ($teams as $team): ?>
                        <tr>
                            <td>#<?= (int) $team['team_id'] ?></td>
                            <td><strong><?= e($team['team_name']) ?></strong></td>
                            <td><?= e($team['country']) ?></td>
                            <td><?= (int) $team['player_count'] ?> Players</td>
                            <td><?= (int) $team['tournament_count'] ?> Tournaments</td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <div class="card-title">➕ Add New Team</div>
        </div>
        <form action="index.php" method="POST">
            <?= Csrf::field() ?>
            <input type="hidden" name="action" value="team.create">

            <div class="form-group">
                <label>Team Name * (Unique)</label>
                <input type="text" name="team_name" class="form-control"
                       placeholder="e.g., Delhi Royals" required>
            </div>
            <div class="form-group">
                <label>Country *</label>
                <input type="text" name="country" class="form-control"
                       placeholder="e.g., India" required>
            </div>
            <button type="submit" class="btn-submit">Save Team</button>
        </form>
    </div>
</div>
