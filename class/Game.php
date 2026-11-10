<?php
/**
 * Class Game -> mengontrol tabel `games`.
 * Tabel ini punya foreign key `genre_id` ke tabel `genres`.
 */
class Game
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    // READ: semua game + nama genre (JOIN)
    public function getAll(): array
    {
        $stmt = $this->db->prepare(
            "SELECT g.*, ge.name AS genre_name
             FROM games g
             JOIN genres ge ON ge.id = g.genre_id
             ORDER BY g.title ASC"
        );
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // READ: satu game berdasarkan id
    public function getById(int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM games WHERE id = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    // CREATE
    public function create(string $title, int $genreId, string $developer, int $releaseYear, float $rating, int $price): bool
    {
        $stmt = $this->db->prepare(
            "INSERT INTO games (title, genre_id, developer, release_year, rating, price)
             VALUES (?, ?, ?, ?, ?, ?)"
        );
        return $stmt->execute([$title, $genreId, $developer, $releaseYear, $rating, $price]);
    }

    // UPDATE
    public function update(int $id, string $title, int $genreId, string $developer, int $releaseYear, float $rating, int $price): bool
    {
        $stmt = $this->db->prepare(
            "UPDATE games
             SET title = ?, genre_id = ?, developer = ?, release_year = ?, rating = ?, price = ?
             WHERE id = ?"
        );
        return $stmt->execute([$title, $genreId, $developer, $releaseYear, $rating, $price, $id]);
    }

    // DELETE
    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM games WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
