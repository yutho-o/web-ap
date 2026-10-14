<?php
// Traduit un lien d'erreur Xdebug en lien vers VS Code, puis redirige le
// navigateur vers celui-ci.
//
// Ce fichier ne s'ouvre jamais directement : c'est Xdebug qui l'appelle
// (voir xdebug.file_link_format dans docker/web/conf.d/xdebug.ini),
// automatiquement, quand on clique sur un nom de fichier sur une page
// d'erreur PHP.
//
// Pourquoi cette etape est necessaire : le chemin transmis par Xdebug
// (parametre « f ») est celui vu par le serveur, DANS le conteneur
// (/var/www/html/...). VS Code, lui, tourne sur le poste, ou ce dossier
// s'appelle autrement (cours/...). Ce script fait la conversion, a
// l'aide de HOST_COURS_DIR, transmis par docker-compose.yml.

$racineConteneur = '/var/www/html';
$fichierConteneur = $_GET['f'] ?? '';
$ligne = (int) ($_GET['l'] ?? 1);

$racineHote = getenv('HOST_COURS_DIR');

$cheminValide = $racineHote !== false
    && str_starts_with($fichierConteneur, $racineConteneur . '/');

if (!$cheminValide) {
    http_response_code(400);
    header('Content-Type: text/plain; charset=utf-8');
    echo "Impossible d'ouvrir ce fichier dans l'editeur : chemin inattendu.";
    exit;
}

$cheminRelatif = substr($fichierConteneur, strlen($racineConteneur));
$cheminHote = $racineHote . $cheminRelatif;

// VS Code ne comprend un chemin brut (vscode://file/...) que si l'editeur
// tourne sur le meme systeme de fichiers que celui indique par le chemin.
// Ce n'est pas toujours le cas : sous WSL, VS Code tourne cote Windows et
// ne sait pas lire un chemin Linux directement ; en Remote-SSH, il tourne
// sur le poste du developpeur, pas sur la machine qui sert la page. Dans
// ces deux cas, il faut adresser le lien a une « autorite » particuliere
// plutot qu'au systeme de fichiers local.
$autoriteForcee = getenv('VSCODE_REMOTE_AUTHORITY') ?: '';
$distroWsl = getenv('HOST_WSL_DISTRO') ?: '';

if ($autoriteForcee !== '') {
    // Remote-SSH (ou autre cas particulier), regle explicitement par
    // docker-compose.yml : voir cours/verif/README.md.
    $cible = 'vscode://vscode-remote/' . $autoriteForcee . $cheminHote . ':' . $ligne;
} elseif ($distroWsl !== '') {
    // WSL : "wsl+<distribution>" est l'autorite que VS Code utilise pour
    // les fenetres ouvertes avec l'extension Remote - WSL.
    $cible = 'vscode://vscode-remote/wsl+' . $distroWsl . $cheminHote . ':' . $ligne;
} else {
    // VS Code tourne directement sur la machine qui sert la page
    // (Linux ou macOS sans conteneur/VM intermediaire).
    $cible = 'vscode://file/' . $cheminHote . ':' . $ligne;
}

header('Location: ' . $cible);
