<?php
/**
 * Front controller: every page and every form action goes through here.
 *
 * GET  index.php?page=<page>       -> render a page
 * POST index.php (action=<name>)   -> run a form action, then redirect (PRG)
 */

declare(strict_types=1);

require dirname(__DIR__) . '/app/autoload.php';

use App\Controllers\DashboardController;
use App\Controllers\MatchController;
use App\Controllers\PlayerController;
use App\Controllers\StandingsController;
use App\Controllers\StaffController;
use App\Controllers\StatsController;
use App\Controllers\TeamController;
use App\Controllers\TournamentController;

session_start();

/** @var array<string, array{0: class-string, 1: string}> $pages */
$pages = [
    'dashboard'   => [DashboardController::class, 'index'],
    'tournaments' => [TournamentController::class, 'index'],
    'teams'       => [TeamController::class, 'index'],
    'players'     => [PlayerController::class, 'index'],
    'matches'     => [MatchController::class, 'index'],
    'standings'   => [StandingsController::class, 'index'],
    'stats'       => [StatsController::class, 'index'],
    'staff'       => [StaffController::class, 'index'],
];

/** @var array<string, array{0: class-string, 1: string}> $actions */
$actions = [
    'team.create'       => [TeamController::class, 'create'],
    'player.create'     => [PlayerController::class, 'create'],
    'tournament.create' => [TournamentController::class, 'create'],
    'match.create'      => [MatchController::class, 'create'],
    'staff.create'      => [StaffController::class, 'create'],
];

/**
 * Render a readable error page (details only when APP_DEBUG is on).
 */
function renderErrorPage(Throwable $error): void
{
    http_response_code(500);

    $config = require BASE_PATH . '/config/config.php';
    $debug = $config['app']['debug'];

    $detail = '';
    if ($debug) {
        $detail = '<pre>' . htmlspecialchars(
            $error->getMessage() . "\n\n" . $error->getTraceAsString(),
            ENT_QUOTES,
            'UTF-8'
        ) . '</pre>';
    }

    echo '<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8">'
        . '<title>Server Error</title></head>'
        . '<body style="font-family: sans-serif; background: #0b132b; color: #f8fafc; padding: 3rem;">'
        . '<h1>Something went wrong</h1>'
        . '<p>The request could not be completed.</p>'
        . $detail
        . '</body></html>';
}

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $action = (string) ($_POST['action'] ?? '');

        if (!isset($actions[$action])) {
            http_response_code(404);
            exit('Unknown form action.');
        }

        [$class, $method] = $actions[$action];
        (new $class())->{$method}();
    }

    $page = (string) ($_GET['page'] ?? 'dashboard');

    if (!isset($pages[$page])) {
        $page = 'dashboard';
    }

    [$class, $method] = $pages[$page];
    (new $class())->{$method}();
} catch (Throwable $error) {
    renderErrorPage($error);
}
