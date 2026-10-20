<?php
/**
 * Dashboard: summary counts, recent results, upcoming fixtures.
 */

$counts = $counts ?? [];
?>
<div class="stats-grid">
    <div class="stat-card">
        <div class="label">Total Tournaments</div>
        <div class="value"><?= (int) ($counts['total_tournaments'] ?? 0) ?></div>
    </div>
    <div class="stat-card">
        <div class="label">Registered Teams</div>
        <div class="value"><?= (int) ($counts['total_teams'] ?? 0) ?></div>
    </div>
    <div class="stat-card">
        <div class="label">Active Players</div>
        <div class="value"><?= (int) ($counts['total_players'] ?? 0) ?></div>
    </div>
    <div class="stat-card">
        <div class="label">Total Matches</div>
        <div class="value"><?= (int) ($counts['total_matches'] ?? 0) ?></div>
    </div>
    <div class="stat-card">
        <div class="label">Support Staff</div>
        <div class="value"><?= (int) ($counts['total_staff'] ?? 0) ?></div>
    </div>
</div>

<div class="layout-split" style="grid-template-columns: 1.3fr 1fr;">
    <!-- Recent Match Results -->
    <div class="card">
        <div class="card-header">
            <div class="card-title">🏅 Recent Match Results</div>
        </div>
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Format</th>
                        <th>Matchup</th>
                        <th>Result Summary</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($completedMatches)): ?>
                        <tr><td colspan="4">No completed matches found.</td></tr>
                    <?php endif; ?>
                    <?php foreach ($completedMatches as $match): ?>
                        <tr>
                            <td><?= e($match['match_date']) ?></td>
                            <td><span class="badge badge-<?= strtolower(e($match['format'])) ?>"><?= e($match['format']) ?></span></td>
                            <td>
                                <strong><?= e($match['team1_name']) ?></strong> vs
                                <strong><?= e($match['team2_name']) ?></strong><br>
                                <small style="color: var(--text-muted)"><?= e($match['tournament_name']) ?></small>
                            </td>
                            <td style="color: #34d399; font-weight: 600;"><?= e($match['result_summary']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Upcoming Scheduled Matches -->
    <div class="card">
        <div class="card-header">
            <div class="card-title">⏳ Upcoming Matches</div>
        </div>
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Format</th>
                        <th>Fixture & Venue</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($upcomingMatches)): ?>
                        <tr><td colspan="3">No upcoming scheduled matches.</td></tr>
                    <?php endif; ?>
                    <?php foreach ($upcomingMatches as $match): ?>
                        <tr>
                            <td><?= e($match['match_date']) ?></td>
                            <td><span class="badge badge-<?= strtolower(e($match['format'])) ?>"><?= e($match['format']) ?></span></td>
                            <td>
                                <strong><?= e($match['team1_name']) ?> vs <?= e($match['team2_name']) ?></strong><br>
                                <small style="color: var(--text-muted)">
                                    📍 <?= e($match['stadium_name']) ?>, <?= e($match['city']) ?>
                                </small>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
