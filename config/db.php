<?php
/**
 * Konfigurasi & koneksi database (PDO).
 * Ubah nilai properti di bawah sesuai environment kamu.
 */
class Database
{
    private string $host   = 'localhost';
    private string $dbname = 'db_ps5_games';
    private string $user   = 'root';
    private string $pass   = '';

    public function getConnection(): PDO
    {
        try {
            $pdo = new PDO(
                "mysql:host={$this->host};dbname={$this->dbname};charset=utf8mb4",
                $this->user,
                $this->pass,
                [
                    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES   => false, // paksa prepared statement asli
                ]
            );
            return $pdo;
        } catch (PDOException $e) {
            die('Koneksi database gagal: ' . $e->getMessage());
        }
    }
}
