-- =====================================================
-- Database: db_ps5_games  (PEES5 PICKMOK)
-- Jalankan file ini via phpMyAdmin (tab SQL / Import)
-- =====================================================

CREATE DATABASE IF NOT EXISTS db_ps5_games
    CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE db_ps5_games;

-- Hapus tabel lama (urutan: anak dulu, baru induk)
DROP TABLE IF EXISTS picks;
DROP TABLE IF EXISTS games;
DROP TABLE IF EXISTS genres;

-- -----------------------------------------------------
-- Tabel 1: genres (induk)
-- -----------------------------------------------------
CREATE TABLE genres (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    name        VARCHAR(100) NOT NULL UNIQUE,
    description VARCHAR(255) NULL
);

-- -----------------------------------------------------
-- Tabel 2: games (FK -> genres)
-- -----------------------------------------------------
CREATE TABLE games (
    id           INT AUTO_INCREMENT PRIMARY KEY,
    title        VARCHAR(150) NOT NULL,
    genre_id     INT NOT NULL,
    developer    VARCHAR(100) NOT NULL,
    release_year INT NOT NULL,
    rating       DECIMAL(3,1) NOT NULL DEFAULT 0.0,
    price        INT NOT NULL DEFAULT 0,
    FOREIGN KEY (genre_id) REFERENCES genres(id)
        ON UPDATE CASCADE
        ON DELETE RESTRICT
);

-- -----------------------------------------------------
-- Tabel 3: picks (FK -> games)
-- -----------------------------------------------------
CREATE TABLE picks (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    game_id     INT NOT NULL,
    player_name VARCHAR(100) NOT NULL,
    reason      VARCHAR(255) NULL,
    picked_at   DATE NOT NULL,
    FOREIGN KEY (game_id) REFERENCES games(id)
        ON UPDATE CASCADE
        ON DELETE CASCADE
);

-- -----------------------------------------------------
-- Data awal
-- -----------------------------------------------------
INSERT INTO genres (name, description) VALUES
('Action Open World', 'Dunia terbuka luas dengan aksi dan misi bebas'),
('Sports', 'Simulasi olahraga seperti sepak bola dan basket'),
('Racing', 'Balapan mobil dan simulasi berkendara'),
('Action Adventure', 'Petualangan bercerita dengan aksi intens'),
('Fighting', 'Pertarungan satu lawan satu');

INSERT INTO games (title, genre_id, developer, release_year, rating, price) VALUES
('Grand Theft Auto Jomok', 1, 'Rockstar Games', 2022, 9.5, 400000),
('EA Suki Liyar', 2, 'EA Vancouver', 2025, 8.0, 899000),
('Gran Ambasi', 3, 'Polyphony Digital', 2022, 8.8, 799000),
('God of War Ragnamok', 4, 'Santa Monica Studio', 2022, 9.4, 729000),
('Marvel''s Spider-Man 2', 4, 'Insomniac Games', 2023, 9.0, 879000),
('Tekken 8', 5, 'Bandai Namco', 2024, 8.7, 799000);

INSERT INTO picks (game_id, player_name, reason, picked_at) VALUES
(1, 'Rizky', 'Dunia terbuka seru dan bisa main bareng di GTA Online', '2026-11-01'),
(2, 'Dina', 'Mau mabar bola sama teman kosan', '2026-11-03'),
(4, 'Fajar', 'Cerita dan combat-nya mantap', '2026-11-05');
