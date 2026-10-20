<?php
/**
 * Matches tab: match list with format filter, schedule/record form.
 */

use App\Core\Csrf;

$matches = $matches ?? [];
$teams = $teams ?? [];
$tournaments = $tournaments ?? [];
$venues = $venues ?? [];
$selectedFormat = $selectedFormat ?? 'ALL';

$formatFilters = ['ALL', 'T20', 'ODI', 'Test'];
?>
<div class="layout-split">
    <div class="card">
        <div class="card-header">
            <div class="card-title">📅 Match Schedule & Results</div>
            <div class="filter-bar">
                <span style="font-size: 0.8rem; color: var(--text-muted);">Format Filter:</span>
                <?php foreach ($formatFilters as $filter): ?>
                    <a href="<?= e(url('matches', ['format' => $filter])) ?>"
                       class="filter-pill<?= $selectedFormat === $filter ? ' active' : '' ?>">
                        <?= $filter === 'ALL' ? 'All' : e($filter) ?>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>Date & Format</th>
                        <th>Tournament & Teams</th>
                        <th>Venue & Officials</th>
                        <th>Status</th>
                        <th>Result Summary</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($matches)): ?>
                        <tr><td colspan="5">No matches found for this format filter.</td></tr>
                    <?php endif; ?>
                    <?php foreach ($matches as $match): ?>
                        <tr>
                            <td>
                                <strong><?= e($match['match_date']) ?></strong><br>
                                <span class="badge badge-<?= strtolower(e($match['format'])) ?>"><?= e($match['format']) ?></span>
                            </td>
                            <td>
                                <strong><?= e($match['team1_name']) ?> vs <?= e($match['team2_name']) ?></strong><br>
                                <small style="color: var(--text-muted)"><?= e($match['tournament_name']) ?></small>
                            </td>
                            <td>
                                📍 <?= e($match['stadium_name']) ?> (<?= e($match['city']) ?>)<br>
                                <?php if (!empty($match['officials'])): ?>
                                    <small style="color: var(--text-muted)">⚖️ <?= e($match['officials']) ?></small>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span class="badge badge-<?= strtolower(e($match['match_status'])) ?>"><?= e($match['match_status']) ?></span>
                            </td>
                            <td>
                                <?php if ($match['match_status'] === 'Completed'): ?>
                                    <strong style="color: #34d399;"><?= e($match['result_summary']) ?></strong>
                                <?php else: ?>
                                    <span style="color: var(--text-muted);"><?= e($match['result_summary'] ?: 'Upcoming') ?></span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <div class="card-title">➕ Schedule / Record Match</div>
        </div>
        <form action="index.php" method="POST">
            <?= Csrf::field() ?>
            <input type="hidden" name="action" value="match.create">

            <div class="form-group">
                <label>Tournament *</label>
                <select name="tournament_id" class="form-control" required>
                    <?php foreach ($tournaments as $tournament): ?>
                        <option value="<?= (int) $tournament['tournament_id'] ?>">
                            <?= e($tournament['tournament_name']) ?> (<?= e($tournament['season']) ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Format *</label>
                <select name="format" class="form-control" required>
                    <option value="T20">T20</option>
                    <option value="ODI">ODI</option>
                    <option value="Test">Test</option>
                </select>
            </div>
            <div class="form-group">
                <label>Team 1 *</label>
                <select name="team1_id" class="form-control" required>
                    <?php foreach ($teams as $team): ?>
                        <option value="<?= (int) $team['team_id'] ?>"><?= e($team['team_name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Team 2 * (Cannot be same as Team 1)</label>
                <select name="team2_id" class="form-control" required>
                    <?php $teamIndex = 0;
                    foreach ($teams as $team):
                        $teamIndex++; ?>
                        <option value="<?= (int) $team['team_id'] ?>"<?= $teamIndex === 2 ? ' selected' : '' ?>>
                            <?= e($team['team_name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Venue *</label>
                <select name="venue_id" class="form-control" required>
                    <?php foreach ($venues as $venue): ?>
                        <option value="<?= (int) $venue['venue_id'] ?>">
                            <?= e($venue['stadium_name']) ?>, <?= e($venue['city']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Match Date *</label>
                <input type="date" name="match_date" class="form-control"
                       value="2026-11-25" required>
            </div>
            <div class="form-group">
                <label>Match Status *</label>
                <select name="match_status" class="form-control">
                    <option value="Scheduled">Scheduled (Upcoming)</option>
                    <option value="Completed">Completed</option>
                </select>
            </div>
            <div class="form-group">
                <label>Result Type (If Completed)</label>
                <select name="result_type" class="form-control">
                    <option value="">-- None (Scheduled) --</option>
                    <option value="WIN">WIN</option>
                    <option value="TIE">TIE</option>
                    <option value="DRAW">DRAW</option>
                    <option value="NO_RESULT">NO_RESULT</option>
                </select>
            </div>
            <div class="form-group">
                <label>Winner Team (If Result = WIN)</label>
                <select name="winner_team_id" class="form-control">
                    <option value="">-- No Winner / Tie / Draw / Scheduled --</option>
                    <?php foreach ($teams as $team): ?>
                        <option value="<?= (int) $team['team_id'] ?>"><?= e($team['team_name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Result / Match Summary</label>
                <input type="text" name="result_summary" class="form-control"
                       placeholder="e.g., Upcoming Group Match">
            </div>
            <button type="submit" class="btn-submit">Save Match</button>
        </form>
    </div>
</div>
