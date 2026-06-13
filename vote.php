<?php
/**
 * vote.php
 * Traitement du vote : vérifie si l'étudiant a déjà voté, puis enregistre le vote
 */

require_once 'connexion.php';

/**
 * Redirige vers index.php avec un message
 */
function redirigerAvecMessage(string $message, string $type = 'info'): void
{
    header('Location: index.php?message=' . urlencode($message) . '&type=' . urlencode($type));
    exit;
}

// Vérifier que la requête est bien en POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

// Récupérer et nettoyer les données du formulaire
$id_candidat  = filter_input(INPUT_POST, 'id_candidat', FILTER_VALIDATE_INT);
$id_etudiant  = trim(filter_input(INPUT_POST, 'id_etudiant', FILTER_SANITIZE_SPECIAL_CHARS) ?? '');

// Validation des données
if (!$id_candidat || empty($id_etudiant)) {
    redirigerAvecMessage('Veuillez remplir tous les champs correctement.', 'error');
}

// Limiter la longueur de l'identifiant étudiant
if (strlen($id_etudiant) > 20) {
    redirigerAvecMessage('L\'identifiant étudiant est trop long (20 caractères max).', 'error');
}

try {
    // Vérifier que le candidat existe
    $stmtCandidat = $pdo->prepare('SELECT id FROM candidats WHERE id = :id');
    $stmtCandidat->execute([':id' => $id_candidat]);

    if (!$stmtCandidat->fetch()) {
        redirigerAvecMessage('Candidat introuvable.', 'error');
    }

    // Vérifier si l'étudiant a déjà voté (requête préparée PDO)
    $stmtVerif = $pdo->prepare('SELECT id FROM votes WHERE id_etudiant = :id_etudiant');
    $stmtVerif->execute([':id_etudiant' => $id_etudiant]);

    if ($stmtVerif->fetch()) {
        // L'étudiant existe déjà dans la table votes
        redirigerAvecMessage('Vous avez déjà voté.', 'warning');
    }

    // Enregistrer le vote (requête préparée PDO)
    $stmtInsert = $pdo->prepare(
        'INSERT INTO votes (id_etudiant, id_candidat) VALUES (:id_etudiant, :id_candidat)'
    );
    $stmtInsert->execute([
        ':id_etudiant' => $id_etudiant,
        ':id_candidat' => $id_candidat
    ]);

    redirigerAvecMessage('Vote enregistré avec succès.', 'success');

} catch (PDOException $e) {
    // Gestion de la contrainte UNIQUE (double vote simultané)
    if ($e->getCode() == 23000) {
        redirigerAvecMessage('Vous avez déjà voté.', 'warning');
    }
    redirigerAvecMessage('Une erreur est survenue lors de l\'enregistrement du vote.', 'error');
}
