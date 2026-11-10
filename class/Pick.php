<?php
/**
 * Class Pick -> mengontrol tabel `picks` (daftar game pilihan user).
 * Tabel ini punya foreign key `game_id` ke tabel `games`.
 */
class Pick
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    // READ: semua pilihan + judul game (JOIN)
    public function getAll(): array
    {
        $stmt = $this->db->prepare(
            "SELECT p.*, g.title AS game_title
             FROM picks p
             JOIN games g ON g.id = p.game_id
             ORDER BY p.picked_at DESC, p.id DESC"
        );
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // READ: satu pilihan berdasarkan id
    public function getById(int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM picks WHERE id = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    // CREATE
    public function create(int $gameId, string $playerName, string $reason): bool
    {
        $stmt = $this->db->prepare(
            "INSERT INTO picks (game_id, player_name, reason, picked_at)
             VALUES (?, ?, ?, CURDATE())"
        );
        return $stmt->execute([$gameId, $playerName, $reason]);
    }

    // UPDATE
    public function update(int $id, int $gameId, string $playerName, string $reason): bool
    {
        $stmt = $this->db->prepare(
            "UPDATE picks SET game_id = ?, player_name = ?, reason = ? WHERE id = ?"
        );
        return $stmt->execute([$gameId, $playerName, $reason, $id]);
    }

    // DELETE
    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM picks WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
