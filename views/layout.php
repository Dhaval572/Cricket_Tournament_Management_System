<?php
/**
 * Shared page shell: header, navigation, flash messages, page content.
 *
 * Expects:
 *   $activePage  string  current page key for navigation highlight
 *   $content     string  rendered page body
 */

use App\Core\Flash;

$activePage = $activePage ?? 'dashboard';
$flashMessages = Flash::pull();

$tabs = [
    'dashboard'   => '📊 Dashboard',
    'tournaments' => '🏆 Tournaments',
    'teams'       => '🛡️ Teams',
    'players'     => '🧢 Players',
    'matches'     => '📅 Matches (T20/ODI/Test)',
    'standings'   => '📈 Standings',
    'stats'       => '⚡ Player Statistics',
    'staff'       => '🩺 Support Staff',
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cricket Tournament Management System</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

    <header>
        <div class="brand">
            <div class="brand-icon">🏏</div>
            <div>
                <h1>Cricket Tournament Management System</h1>
                <span>Fixtures · Standings · Statistics</span>
            </div>
        </div>
    </header>

    <nav>
        <?php foreach ($tabs as $tabKey => $tabLabel): ?>
            <a class="nav-btn<?= $activePage === $tabKey ? ' active' : '' ?>"
               href="<?= e(url($tabKey)) ?>"><?= $tabLabel ?></a>
        <?php endforeach; ?>
    </nav>

    <main>
        <?php require BASE_PATH . '/views/partials/flash.php'; ?>

        <?= $content ?>
    </main>

</body>
</html>
