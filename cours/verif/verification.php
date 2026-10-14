<?php
// Page de vérification de l’installation.
// À ouvrir une fois avant la première séance PHP, puis à oublier.
// Ce fichier n’est pas un modèle de code : il sert seulement de test.

$hote = getenv('DB_HOST') ?: 'db';        // « db » sous Docker (service docker-compose.yml)
$base = 'esport';
$utilisateur = 'etudiant';
$motDePasse = 'etudiant';

$versionPhp = PHP_VERSION;
$pdoDisponible = extension_loaded('pdo_mysql');

$connexionOk = false;
$messageBase = '';
$nombreEquipes = null;

if ($pdoDisponible) {
    try {
        $bdd = new PDO(
            'mysql:host=' . $hote . ';dbname=' . $base . ';charset=utf8mb4',
            $utilisateur,
            $motDePasse,
            [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
        );
        $connexionOk = true;
        $nombreEquipes = $bdd->query('SELECT COUNT(*) FROM equipe')->fetchColumn();
    } catch (PDOException $e) {
        $messageBase = $e->getMessage();
    }
}

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Vérification de l’installation</title>
    <style>
        body { font-family: system-ui, sans-serif; max-width: 40rem; margin: 2rem auto; padding: 0 1rem; }
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #ccc; padding: 0.5rem 0.75rem; text-align: left; }
        th { background: #f4f4f4; }
        .ok { color: #17692a; font-weight: bold; }
        .ko { color: #a3231b; font-weight: bold; }
        code { background: #f4f4f4; padding: 0.1rem 0.3rem; }
    </style>
</head>
<body>

    <h1>Vérification de l’installation</h1>

    <p>Cette page teste le serveur PHP et la connexion à la base de données.</p>

    <table>
        <tr>
            <th>Le serveur exécute le PHP</th>
            <td class="ok">oui — version <?= htmlspecialchars($versionPhp) ?></td>
        </tr>
        <tr>
            <th>Extension PDO MySQL</th>
            <td class="<?= $pdoDisponible ? 'ok' : 'ko' ?>">
                <?= $pdoDisponible ? 'disponible' : 'absente' ?>
            </td>
        </tr>
        <tr>
            <th>Connexion à la base</th>
            <td class="<?= $connexionOk ? 'ok' : 'ko' ?>">
                <?= $connexionOk ? 'établie' : 'échouée' ?>
            </td>
        </tr>
        <tr>
            <th>Équipes enregistrées</th>
            <td>
                <?php if ($nombreEquipes !== null): ?>
                    <?= (int) $nombreEquipes ?> (six attendues)
                <?php else: ?>
                    <span class="ko">non vérifiable</span>
                <?php endif; ?>
            </td>
        </tr>
    </table>

    <?php if (!$connexionOk && $messageBase !== ''): ?>
        <h2>Message d’erreur</h2>
        <p><code><?= htmlspecialchars($messageBase) ?></code></p>
        <p>
            Ce message est à lire attentivement : il indique en général si le
            serveur de base de données est injoignable, si les identifiants
            sont refusés, ou si la base n’a pas encore été importée.
        </p>
    <?php endif; ?>

    <p>
        Si toutes les lignes sont vertes, l’installation est complète.
    </p>

</body>
</html>
