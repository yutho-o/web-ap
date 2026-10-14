<?php
// Page servant a tester le debogueur pas a pas (Xdebug + VS Code).
// Elle n'a aucun autre role : le calcul qu'elle fait est sans interet,
// seul compte le fait de pouvoir l'arreter en cours de route pour
// regarder ce que contiennent les variables.
//
// Mode d'emploi complet : cours/verif/README.md

/**
 * Additionne les points marques par une equipe sur plusieurs manches.
 */
function totalPoints(array $points): int
{
    $total = 0;

    foreach ($points as $numeroManche => $pointsDeLaManche) {
        // POINT D'ARRET : cliquer a gauche du numero de cette ligne.
        // L'execution s'arretera ici a chaque tour de boucle.
        $total = $total + $pointsDeLaManche;
    }

    return $total;
}

$equipe = 'Les Renards';
$points = [12, 8, 25, 4, 17];

$total = totalPoints($pointss);
$moyenne = $total / count($points);
$meilleure = max($points);

?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Test du debogueur</title>
    <style>
        body {
            font-family: system-ui, sans-serif;
            max-width: 40rem;
            margin: 2rem auto;
            padding: 0 1rem;
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }

        th,
        td {
            border: 1px solid #ccc;
            padding: 0.5rem 0.75rem;
            text-align: left;
        }

        th {
            background: #f4f4f4;
        }

        code {
            background: #f4f4f4;
            padding: 0.1rem 0.3rem;
        }
    </style>
</head>

<body>

    <h1>Test du debogueur</h1>

    <p>
        Cette page calcule le total des points de l'equipe
        <strong><?= htmlspecialchars($equipe) ?></strong>.
        Les valeurs affichees ci-dessous sont normales : ce n'est pas le
        resultat qui est interessant, mais la possibilite d'arreter le
        programme avant qu'il ne l'affiche.
    </p>

    <table>
        <tr>
            <th>Points par manche</th>
            <td><?= htmlspecialchars(implode(', ', $points)) ?></td>
        </tr>
        <tr>
            <th>Total</th>
            <td><?= (int) $total ?></td>
        </tr>
        <tr>
            <th>Moyenne</th>
            <td><?= htmlspecialchars(number_format($moyenne, 2, ',', ' ')) ?></td>
        </tr>
        <tr>
            <th>Meilleure manche</th>
            <td><?= (int) $meilleure ?></td>
        </tr>
    </table>

    <h2>Ce qu'il faut obtenir</h2>

    <p>
        Le debogueur fonctionne si, apres avoir pose un point d'arret dans
        la fonction <code>totalPoints()</code> et recharge cette page, VS
        Code passe au premier plan, surligne la ligne en jaune et affiche
        <code>$total</code> et <code>$pointsDeLaManche</code> dans le
        panneau « Variables ».
    </p>

    <p>
        Tant que l'execution est arretee, le navigateur attend : l'onglet
        tourne dans le vide. C'est normal, la page ne s'affichera qu'une
        fois le programme relance ou termine.
    </p>

    <p>
        La marche a suivre detaillee est dans <code>cours/verif/README.md</code>.
        Pour verifier que Xdebug est bien installe cote serveur :
        <a href="config-xdebug.php">config-xdebug.php</a>.
    </p>

</body>

</html>
