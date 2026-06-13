<?php
/**
 * resultat.php
 * Affiche les résultats du vote avec une requête SQL GROUP BY
 */

require_once 'connexion.php';

/**
 * Requête GROUP BY : compte le nombre de votes par candidat
 * LEFT JOIN pour afficher aussi les candidats sans vote (0 vote)
 */
$sql = '
    SELECT
        c.nom,
        COUNT(v.id) AS nombre_votes
    FROM candidats c
    LEFT JOIN votes v ON v.id_candidat = c.id
    GROUP BY c.id, c.nom
    ORDER BY nombre_votes DESC, c.nom ASC
';

$stmt = $pdo->query($sql);
$resultats = $stmt->fetchAll();

// Calcul du total des votes pour les pourcentages
$totalVotes = array_sum(array_column($resultats, 'nombre_votes'));
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Résultats — Système de Vote Électronique</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

    <header class="header">
        <div class="container">
            <h1>Résultats du vote</h1>
            <p class="subtitle">Classement des candidats par nombre de voix</p>
            <nav class="nav-links">
                <a href="index.php" class="nav-link">Accueil</a>
                <a href="resultat.php" class="nav-link active">Résultats</a>
            </nav>
        </div>
    </header>

    <main class="container">

        <!-- Résumé global -->
        <div class="stats-summary">
            <p>Total des votes enregistrés : <strong><?php echo (int) $totalVotes; ?></strong></p>
        </div>

        <!-- Tableau des résultats -->
        <section class="resultats-section">
            <?php if (empty($resultats)): ?>
                <p class="no-data">Aucun résultat disponible.</p>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="resultats-table">
                        <thead>
                            <tr>
                                <th>Rang</th>
                                <th>Nom du candidat</th>
                                <th>Nombre de votes</th>
                                <th>Pourcentage</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $rang = 1;
                            foreach ($resultats as $ligne):
                                $nbVotes = (int) $ligne['nombre_votes'];
                                $pourcentage = $totalVotes > 0
                                    ? round(($nbVotes / $totalVotes) * 100, 1)
                                    : 0;
                            ?>
                                <tr>
                                    <td><?php echo $rang++; ?></td>
                                    <td><?php echo htmlspecialchars($ligne['nom']); ?></td>
                                    <td><span class="badge-votes"><?php echo $nbVotes; ?></span></td>
                                    <td>
                                        <div class="barre-progression">
                                            <div
                                                class="barre-remplissage"
                                                style="width: <?php echo $pourcentage; ?>%;"
                                            ></div>
                                            <span class="barre-texte"><?php echo $pourcentage; ?>%</span>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </section>

        <p class="back-link">
            <a href="index.php" class="btn-secondary">← Retour au vote</a>
        </p>
    </main>

    <footer class="footer">
        <div class="container">
            <p>&copy; <?php echo date('Y'); ?> Système de Vote Électronique — Projet TS Développement Informatique</p>
        </div>
    </footer>

</body>
</html>
