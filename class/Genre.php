<?php
/**
 * Class Genre -> mengontrol tabel `genres`.
 */
class Genre
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    // READ: semua genre
    public function getAll(): array
    {
        $stmt = $this->db->prepare("SELECT * FROM genres ORDER BY name ASC");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // READ: satu genre berdasarkan id
    public function getById(int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM genres WHERE id = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    // CREATE
    public function create(string $name, string $description): bool
    {
        $stmt = $this->db->prepare("INSERT INTO genres (name, description) VALUES (?, ?)");
        return $stmt->execute([$name, $description]);
    }

    // UPDATE
    public function update(int $id, string $name, string $description): bool
    {
        $stmt = $this->db->prepare("UPDATE genres SET name = ?, description = ? WHERE id = ?");
        return $stmt->execute([$name, $description, $id]);
    }

    // DELETE
    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM genres WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
