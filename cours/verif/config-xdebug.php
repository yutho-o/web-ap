<?php
// Page de verification de la configuration de Xdebug, cote serveur.
// Elle repond a une seule question : le serveur est-il pret a etre
// debogue ? Elle ne dit rien de VS Code, qui se teste avec
// test-debogage.php.
//
// Ce fichier n'est pas un modele de code : il sert seulement de test.

// Vues detaillees, affichees seules car elles produisent leur propre page.
$detail = $_GET['detail'] ?? '';

if ($detail === 'xdebug' && function_exists('xdebug_info')) {
    xdebug_info();
    exit;
}

if ($detail === 'php') {
    phpinfo();
    exit;
}

$xdebugCharge = extension_loaded('xdebug');
$version = $xdebugCharge ? phpversion('xdebug') : null;

// Reglages lus dans le fichier de configuration du serveur
// (docker/web/conf.d/xdebug.ini).
$mode = ini_get('xdebug.mode');
// ini_get() rend « 1 » la ou le fichier de configuration porte « yes » :
// on retablit le mot d'origine, plus parlant.
$demarrage = ini_get('xdebug.start_with_request');
$demarrage = ['1' => 'yes', '0' => 'no'][$demarrage] ?? $demarrage;
$hote = ini_get('xdebug.client_host');
$port = ini_get('xdebug.client_port');
$journal = ini_get('xdebug.log');

// « debug » est le mode qui autorise les points d'arret. Les autres
// modes (develop, profile, coverage) ne suffisent pas.
$modeDebug = $xdebugCharge && in_array('debug', array_map('trim', explode(',', (string) $mode)), true);

// Vrai uniquement si une session de debogage est en cours, donc si VS
// Code ecoute au moment ou cette page est demandee.
$sessionActive = $xdebugCharge
    && function_exists('xdebug_is_debugger_active')
    && xdebug_is_debugger_active();

$pretPourDebogage = $xdebugCharge && $modeDebug;

/**
 * Affiche une valeur de reglage, ou une mention explicite si elle est vide.
 */
function valeur(?string $valeur): string
{
    return ($valeur === null || $valeur === '')
        ? '<span class="ko">non defini</span>'
        : '<code>' . htmlspecialchars($valeur) . '</code>';
}

?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Configuration de Xdebug</title>
    <style>
        body { font-family: system-ui, sans-serif; max-width: 44rem; margin: 2rem auto; padding: 0 1rem; }
        table { border-collapse: collapse; width: 100%; margin-bottom: 1.5rem; }
        th, td { border: 1px solid #ccc; padding: 0.5rem 0.75rem; text-align: left; }
        th { background: #f4f4f4; width: 45%; }
        .ok { color: #17692a; font-weight: bold; }
        .ko { color: #a3231b; font-weight: bold; }
        .attente { color: #8a6100; font-weight: bold; }
        code { background: #f4f4f4; padding: 0.1rem 0.3rem; }
    </style>
</head>

<body>

    <h1>Configuration de Xdebug</h1>

    <p>
        Cette page verifie que le serveur PHP est capable d'etre debogue.
        Elle se lit avant <code>test-debogage.php</code> : inutile de
        chercher pourquoi un point d'arret ne fonctionne pas si Xdebug
        n'est pas installe.
    </p>

    <h2>L'essentiel</h2>

    <table>
        <tr>
            <th>Version de PHP</th>
            <td><?= htmlspecialchars(PHP_VERSION) ?></td>
        </tr>
        <tr>
            <th>Extension Xdebug</th>
            <td class="<?= $xdebugCharge ? 'ok' : 'ko' ?>">
                <?= $xdebugCharge
                    ? 'chargee — version ' . htmlspecialchars((string) $version)
                    : 'absente' ?>
            </td>
        </tr>
        <tr>
            <th>Mode « debug » actif</th>
            <td class="<?= $modeDebug ? 'ok' : 'ko' ?>">
                <?= $modeDebug ? 'oui' : 'non' ?>
            </td>
        </tr>
        <tr>
            <th>Serveur pret pour le debogage</th>
            <td class="<?= $pretPourDebogage ? 'ok' : 'ko' ?>">
                <?= $pretPourDebogage ? 'oui' : 'non' ?>
            </td>
        </tr>
        <tr>
            <th>Session de debogage en cours</th>
            <td class="<?= $sessionActive ? 'ok' : 'attente' ?>">
                <?= $sessionActive
                    ? 'oui — VS Code est connecte'
                    : 'non — VS Code n\'ecoute pas' ?>
            </td>
        </tr>
    </table>

    <p>
        La derniere ligne est la seule qui puisse rester orange sans que ce
        soit une erreur : elle passe au vert quand le debogueur de VS Code
        est lance (touche <code>F5</code>, configuration
        « Ecouter Xdebug ») au moment ou l'on recharge cette page.
    </p>

    <h2>Reglages du serveur</h2>

    <p>
        Ces valeurs viennent de <code>docker/web/conf.d/xdebug.ini</code>.
        Il n'y a rien a y changer.
    </p>

    <table>
        <tr>
            <th><code>xdebug.mode</code></th>
            <td><?= valeur($mode) ?></td>
        </tr>
        <tr>
            <th><code>xdebug.start_with_request</code></th>
            <td><?= valeur($demarrage) ?></td>
        </tr>
        <tr>
            <th><code>xdebug.client_host</code></th>
            <td><?= valeur($hote) ?> (la machine ou tourne VS Code)</td>
        </tr>
        <tr>
            <th><code>xdebug.client_port</code></th>
            <td><?= valeur($port) ?> (a retrouver dans <code>.vscode/launch.json</code>)</td>
        </tr>
        <tr>
            <th><code>xdebug.log</code></th>
            <td><?= valeur($journal) ?></td>
        </tr>
    </table>

    <?php if (!$xdebugCharge): ?>
        <h2>Xdebug n'est pas installe</h2>
        <p>
            L'image du serveur n'a pas ete reconstruite depuis l'ajout de
            Xdebug. Depuis la racine du projet :
        </p>
        <p><code>docker compose up -d --build</code></p>
    <?php endif; ?>

    <h2>Aller plus loin</h2>

    <ul>
        <li><a href="?detail=xdebug">Diagnostic complet de Xdebug</a>
            <?= $xdebugCharge ? '' : ' (indisponible : extension absente)' ?></li>
        <li><a href="?detail=php">Configuration complete de PHP (phpinfo)</a></li>
        <li><a href="test-debogage.php">Tester un point d'arret</a></li>
    </ul>

</body>

</html>
