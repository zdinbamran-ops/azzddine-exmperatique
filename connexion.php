<?php
/**
 * connexion.php
 * Fichier de connexion à la base de données avec PDO
 * Configuration par défaut pour XAMPP (localhost, root, pas de mot de passe)
 */

// Paramètres de connexion MySQL (XAMPP par défaut)
$host     = 'localhost';
$dbname   = 'vote_electronique_db';
$user     = 'root';
$password = '';

try {
    // Création de la connexion PDO avec options de sécurité
    $pdo = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
        $user,
        $password,
        [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Affiche les erreurs SQL
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // Résultats sous forme de tableau associatif
            PDO::ATTR_EMULATE_PREPARES   => false                   // Utilise les vraies requêtes préparées
        ]
    );
} catch (PDOException $e) {
    // En cas d'erreur de connexion, afficher un message clair
    die('Erreur de connexion à la base de données : ' . htmlspecialchars($e->getMessage()));
}
