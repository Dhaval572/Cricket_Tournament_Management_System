<?php
/**
 * Support staff tab: staff list and add-staff form.
 */

use App\Core\Csrf;

$staffList = $staffList ?? [];
$teams = $teams ?? [];
?>
<div class="layout-split">
    <div class="card">
        <div class="card-header">
            <div class="card-title">🩺 Team & Tournament Support Staff</div>
        </div>
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Staff Name</th>
                        <th>Role / Designation</th>
                        <th>Country</th>
                        <th>Associated Team</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($staffList)): ?>
                        <tr><td colspan="5">No support staff registered yet.</td></tr>
                    <?php endif; ?>
                    <?php foreach ($staffList as $member): ?>
                        <tr>
                            <td>#<?= (int) $member['staff_id'] ?></td>
                            <td><strong><?= e($member['staff_name']) ?></strong></td>
                            <td><span class="badge badge-odi"><?= e($member['role']) ?></span></td>
                            <td><?= e($member['country']) ?></td>
                            <td><?= e($member['team_name'] ?: 'Neutral / Tournament Official') ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <div class="card-title">➕ Add Support Staff Member</div>
        </div>
        <form action="index.php" method="POST">
            <?= Csrf::field() ?>
            <input type="hidden" name="action" value="staff.create">

            <div class="form-group">
                <label>Staff Full Name *</label>
                <input type="text" name="staff_name" class="form-control"
                       placeholder="e.g., Ricky Ponting" required>
            </div>
            <div class="form-group">
                <label>Role *</label>
                <select name="role" class="form-control" required>
                    <option value="Head Coach">Head Coach</option>
                    <option value="Batting Coach">Batting Coach</option>
                    <option value="Bowling Coach">Bowling Coach</option>
                    <option value="Fielding Coach">Fielding Coach</option>
                    <option value="Lead Physiotherapist">Lead Physiotherapist</option>
                    <option value="Performance Video Analyst">Performance Video Analyst</option>
                    <option value="Strength & Conditioning Coach">Strength & Conditioning Coach</option>
                </select>
            </div>
            <div class="form-group">
                <label>Country *</label>
                <input type="text" name="country" class="form-control"
                       placeholder="e.g., Australia" required>
            </div>
            <div class="form-group">
                <label>Associated Team (Optional)</label>
                <select name="team_id" class="form-control">
                    <option value="">-- Neutral / Tournament Staff --</option>
                    <?php foreach ($teams as $team): ?>
                        <option value="<?= (int) $team['team_id'] ?>"><?= e($team['team_name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit" class="btn-submit">Save Support Staff</button>
        </form>
    </div>
</div>
