<?php
/**
 * Flash notification messages queued by App\Core\Flash.
 */

if (!empty($flashMessages)): ?>
    <div class="flash-container">
        <?php foreach ($flashMessages as $flash): ?>
            <div class="flash <?= e($flash['type']) ?>"><?= e($flash['message']) ?></div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
