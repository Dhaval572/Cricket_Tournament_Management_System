<?php
/**
 * Tournaments tab: tournament list with teams, create form.
 */

use App\Core\Csrf;

$tournaments = $tournaments ?? [];
$teams = $teams ?? [];
?>
<div class="layout-split">
    <div class="card">
        <div class="card-header">
            <div class="card-title">🏆 Official Cricket Tournaments</div>
        </div>
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Tournament Name</th>
                        <th>Season</th>
                        <th>Dates</th>
                        <th>Participating Teams</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($tournaments)): ?>
                        <tr><td colspan="5">No tournaments created yet.</td></tr>
                    <?php endif; ?>
                    <?php foreach ($tournaments as $tournament): ?>
                        <tr>
                            <td>#<?= (int) $tournament['tournament_id'] ?></td>
                            <td><strong><?= e($tournament['tournament_name']) ?></strong></td>
                            <td><span class="badge badge-completed"><?= e($tournament['season']) ?></span></td>
                            <td><?= e($tournament['start_date']) ?> to <?= e($tournament['end_date']) ?></td>
                            <td><?= e($tournament['participating_teams'] ?: 'No teams registered yet') ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <div class="card-title">➕ Create New Tournament</div>
        </div>
        <form action="index.php" method="POST">
            <?= Csrf::field() ?>
            <input type="hidden" name="action" value="tournament.create">

            <div class="form-group">
                <label>Tournament Name *</label>
                <input type="text" name="tournament_name" class="form-control"
                       placeholder="e.g., Asia Cup T20" required>
            </div>
            <div class="form-group">
                <label>Season *</label>
                <input type="text" name="season" class="form-control" value="2026" required>
            </div>
            <div class="form-group">
                <label>Start Date *</label>
                <input type="date" name="start_date" class="form-control"
                       value="2026-11-01" required>
            </div>
            <div class="form-group">
                <label>End Date * (Must be >= Start Date)</label>
                <input type="date" name="end_date" class="form-control"
                       value="2026-11-25" required>
            </div>
            <div class="form-group">
                <label>Select Participating Teams</label>
                <div class="checkbox-list">
                    <?php foreach ($teams as $team): ?>
                        <label class="checkbox-item">
                            <input type="checkbox" name="team_ids[]"
                                   value="<?= (int) $team['team_id'] ?>" checked>
                            <?= e($team['team_name']) ?> (<?= e($team['country']) ?>)
                        </label>
                    <?php endforeach; ?>
                </div>
            </div>
            <button type="submit" class="btn-submit">Create Tournament</button>
        </form>
    </div>
</div>
