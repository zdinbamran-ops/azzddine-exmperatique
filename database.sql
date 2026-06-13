-- ============================================================
-- Système de Vote Électronique - Script SQL
-- Base de données : vote_electronique_db
-- À importer dans phpMyAdmin (XAMPP)
-- ============================================================

CREATE DATABASE IF NOT EXISTS vote_electronique_db
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE vote_electronique_db;

-- Table des candidats
CREATE TABLE IF NOT EXISTS candidats (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    photo VARCHAR(255) NOT NULL,
    programme TEXT NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table des votes (un vote par étudiant grâce à id_etudiant UNIQUE)
CREATE TABLE IF NOT EXISTS votes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_etudiant VARCHAR(20) NOT NULL UNIQUE,
    id_candidat INT NOT NULL,
    date_vote DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_candidat) REFERENCES candidats(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================================
-- Données de test : 3 candidats
-- ============================================================
INSERT INTO candidats (nom, photo, programme) VALUES
(
    'Ahmed El Mansouri',
    'images/ahmed.jpg',
    'Développement des infrastructures universitaires et amélioration des services étudiants.'
),
(
    'Sara Benali',
    'images/sara.jpg',
    'Numérisation des services administratifs et soutien aux activités culturelles.'
),
(
    'Youssef Alaoui',
    'images/youssef.jpg',
    'Création de nouveaux espaces d étude et amélioration du réseau Wi-Fi.'
);