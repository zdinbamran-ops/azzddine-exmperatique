<?php
/**
 * index.php
 * Page d'accueil : affiche tous les candidats sous forme de cartes
 */

require_once 'connexion.php';

// Récupérer tous les candidats depuis la base de données
$stmt = $pdo->query('SELECT id, nom, photo, programme FROM candidats ORDER BY id');
$candidats = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Système de Vote Électronique</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

    <!-- En-tête du site -->
    <header class="header">
        <div class="container">
            <h1>Système de Vote Électronique</h1>
            <p class="subtitle">Élection du délégué de promotion — Choisissez votre candidat</p>
            <nav class="nav-links">
                <a href="index.php" class="nav-link active">Accueil</a>
                <a href="resultat.php" class="nav-link">Résultats</a>
            </nav>
        </div>
    </header>

    <main class="container">

        <!-- Zone d'affichage des messages (succès, erreur, déjà voté) -->
        <?php if (isset($_GET['message'])): ?>
            <div class="alert alert-<?php echo htmlspecialchars($_GET['type'] ?? 'info'); ?>">
                <?php echo htmlspecialchars($_GET['message']); ?>
            </div>
        <?php endif; ?>

        <!-- Grille des cartes candidats -->
        <section class="cards-grid">
            <?php if (empty($candidats)): ?>
                <p class="no-data">Aucun candidat disponible pour le moment.</p>
            <?php else: ?>
                <?php foreach ($candidats as $candidat): ?>
                    <article class="card">
                        <!-- Photo du candidat -->
                        <div class="card-image">
                            <img
                                src="<?php echo htmlspecialchars($candidat['photo']); ?>"
                                alt="Photo de <?php echo htmlspecialchars($candidat['nom']); ?>"
                                onerror="this.src='images/default.svg'"
                            >
                        </div>
                        <div class="card-body">
                            <h2 class="card-title"><?php echo htmlspecialchars($candidat['nom']); ?></h2>
                            <p class="card-programme"><?php echo htmlspecialchars($candidat['programme']); ?></p>
                            <!-- Bouton Voter : ouvre le formulaire modal -->
                            <button
                                type="button"
                                class="btn-voter"
                                data-id="<?php echo (int) $candidat['id']; ?>"
                                data-nom="<?php echo htmlspecialchars($candidat['nom']); ?>"
                            >
                                Voter
                            </button>
                        </div>
                    </article>
                <?php endforeach; ?>
            <?php endif; ?>
        </section>
    </main>

    <!-- Modal : formulaire de vote -->
    <div id="modal-vote" class="modal" aria-hidden="true">
        <div class="modal-content">
            <button type="button" class="modal-close" id="btn-fermer-modal" aria-label="Fermer">&times;</button>
            <h2>Confirmer votre vote</h2>
            <p class="modal-candidat">
                Vous votez pour : <strong id="nom-candidat-selectionne"></strong>
            </p>

            <!-- Formulaire envoyé vers vote.php -->
            <form id="form-vote" action="vote.php" method="POST">
                <!-- ID du candidat sélectionné (champ caché) -->
                <input type="hidden" name="id_candidat" id="id_candidat" value="">

                <div class="form-group">
                    <label for="id_etudiant">Identifiant étudiant</label>
                    <input
                        type="text"
                        id="id_etudiant"
                        name="id_etudiant"
                        placeholder="Ex : ETU2024001"
                        required
                        maxlength="20"
                        pattern="[A-Za-z0-9]+"
                        title="Lettres et chiffres uniquement"
                    >
                </div>

                <div class="form-actions">
                    <button type="button" class="btn-secondary" id="btn-annuler">Annuler</button>
                    <button type="submit" class="btn-voter">Valider le vote</button>
                </div>
            </form>
        </div>
    </div>

    <footer class="footer">
        <div class="container">
            <p>&copy; <?php echo date('Y'); ?> Système de Vote Électronique — Projet TS Développement Informatique</p>
        </div>
    </footer>

    <script src="js/script.js"></script>
</body>
</html>
