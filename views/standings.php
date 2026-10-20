<?php
/**
 * Standings tab: points table per tournament (live SQL aggregation).
 *
 * Points: WIN = 2, TIE / DRAW / NO_RESULT = 1, LOSS = 0.
 * Only completed matches count as played.
 */

$standings = $standings ?? [];
?>
<div class="info-box">
    <strong>📐 Official Points Calculation:</strong><br>
    • <strong>WIN:</strong> 2 Points &nbsp;|&nbsp;
    <strong>TIE / DRAW / NO RESULT:</strong> 1 Point each &nbsp;|&nbsp;
    <strong>LOSS:</strong> 0 Points.<br>
    • Only completed matches count toward <strong>Played (P)</strong>.
    Upcoming fixtures are excluded.
</div>

<?php if (empty($standings)): ?>
    <div class="card">
        <div class="card-body">No tournaments with registered teams yet.</div>
    </div>
<?php endif; ?>

<?php foreach ($standings as $tournamentTitle => $rows): ?>
    <div class="card">
        <div class="card-header">
            <div class="card-title">🏆 <?= e($tournamentTitle) ?> — Points Table</div>
        </div>
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>Pos</th>
                        <th>Team</th>
                        <th>Played (P)</th>
                        <th>Won (W)</th>
                        <th>Lost (L)</th>
                        <th>Tied (T)</th>
                        <th>Draw / NR</th>
                        <th>Points (PTS)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $position = 0; ?>
                    <?php foreach ($rows as $row): $position++; ?>
                        <tr>
                            <td><strong>#<?= $position ?></strong></td>
                            <td><strong><?= e($row['team_name']) ?></strong></td>
                            <td><?= (int) $row['played'] ?></td>
                            <td style="color: #34d399; font-weight: 700;"><?= (int) $row['won'] ?></td>
                            <td style="color: #f87171;"><?= (int) $row['lost'] ?></td>
                            <td><?= (int) $row['tied'] ?></td>
                            <td><?= (int) $row['draw_nr'] ?></td>
                            <td>
                                <span class="badge badge-completed" style="font-size: 0.9rem;">
                                    <?= (int) $row['points'] ?> PTS
                                </span>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
<?php endforeach; ?>
