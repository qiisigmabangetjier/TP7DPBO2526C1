<?php
/**
 * View: CRUD tabel games (punya FK genre_id -> genres)
 * Variabel $db sudah tersedia dari index.php
 */
$game     = new Game($db);
$genre    = new Genre($db);
$editData = null;

// ---- Proses aksi form (POST) ----
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        if (isset($_POST['create']) || isset($_POST['update'])) {
            $title       = trim($_POST['title'] ?? '');
            $genreId     = (int) ($_POST['genre_id'] ?? 0);
            $developer   = trim($_POST['developer'] ?? '');
            $releaseYear = (int) ($_POST['release_year'] ?? 0);
            $rating      = (float) ($_POST['rating'] ?? 0);
            $price       = (int) ($_POST['price'] ?? 0);

            if ($title === '' || $developer === '' || $genreId <= 0) {
                flash('error', 'Judul, genre, dan developer wajib diisi.');
            } elseif ($rating < 0 || $rating > 10) {
                flash('error', 'Rating harus antara 0 sampai 10.');
            } elseif (isset($_POST['create'])) {
                $game->create($title, $genreId, $developer, $releaseYear, $rating, $price);
                flash('success', 'Game berhasil ditambahkan.');
            } else {
                $game->update((int) $_POST['id'], $title, $genreId, $developer, $releaseYear, $rating, $price);
                flash('success', 'Game berhasil diperbarui.');
            }
        } elseif (isset($_POST['delete'])) {
            $game->delete((int) $_POST['id']);
            flash('success', 'Game berhasil dihapus (pilihan terkait ikut terhapus).');
        }
    } catch (PDOException $e) {
        flash('error', 'Terjadi kesalahan database: pastikan genre yang dipilih valid.');
    }
    redirect('index.php?page=games');
}

// ---- Mode edit ----
if (isset($_GET['edit'])) {
    $editData = $game->getById((int) $_GET['edit']);
}

$rows   = $game->getAll();
$genres = $genre->getAll(); // untuk dropdown
?>
<h2>Katalog Game PS5</h2>

<form method="post" class="card form">
    <h3><?= $editData ? 'Edit Game' : 'Tambah Game' ?></h3>
    <?php if ($editData): ?>
        <input type="hidden" name="id" value="<?= h($editData['id']) ?>">
    <?php endif; ?>

    <label>Judul Game</label>
    <input type="text" name="title" maxlength="150" required value="<?= h($editData['title'] ?? '') ?>">

    <label>Genre</label>
    <select name="genre_id" required>
        <option value="">-- Pilih genre --</option>
        <?php foreach ($genres as $g): ?>
            <option value="<?= h($g['id']) ?>" <?= ($editData['genre_id'] ?? '') == $g['id'] ? 'selected' : '' ?>>
                <?= h($g['name']) ?>
            </option>
        <?php endforeach; ?>
    </select>

    <label>Developer</label>
    <input type="text" name="developer" maxlength="100" required value="<?= h($editData['developer'] ?? '') ?>">

    <div class="grid3">
        <div>
            <label>Tahun Rilis</label>
            <input type="number" name="release_year" min="2000" max="2100" required value="<?= h($editData['release_year'] ?? date('Y')) ?>">
        </div>
        <div>
            <label>Rating (0-10)</label>
            <input type="number" name="rating" min="0" max="10" step="0.1" required value="<?= h($editData['rating'] ?? '') ?>">
        </div>
        <div>
            <label>Harga (Rp)</label>
            <input type="number" name="price" min="0" step="1000" required value="<?= h($editData['price'] ?? '') ?>">
        </div>
    </div>

    <div class="actions">
        <?php if ($editData): ?>
            <button type="submit" name="update" class="btn primary">Simpan Perubahan</button>
            <a href="index.php?page=games" class="btn">Batal</a>
        <?php else: ?>
            <button type="submit" name="create" class="btn primary">Tambah</button>
        <?php endif; ?>
    </div>
</form>

<table class="card">
    <thead>
        <tr><th>ID</th><th>Judul</th><th>Genre</th><th>Developer</th><th>Rilis</th><th>Rating</th><th>Harga</th><th>Aksi</th></tr>
    </thead>
    <tbody>
    <?php if (empty($rows)): ?>
        <tr><td colspan="8" class="empty">Belum ada data game.</td></tr>
    <?php endif; ?>
    <?php foreach ($rows as $row): ?>
        <tr>
            <td><?= h($row['id']) ?></td>
            <td><?= h($row['title']) ?></td>
            <td><?= h($row['genre_name']) ?></td>
            <td><?= h($row['developer']) ?></td>
            <td><?= h($row['release_year']) ?></td>
            <td>⭐ <?= h($row['rating']) ?></td>
            <td>Rp <?= number_format((int) $row['price'], 0, ',', '.') ?></td>
            <td class="actions">
                <a href="index.php?page=games&edit=<?= h($row['id']) ?>" class="btn small">Edit</a>
                <form method="post" onsubmit="return confirm('Hapus game ini?')">
                    <input type="hidden" name="id" value="<?= h($row['id']) ?>">
                    <button type="submit" name="delete" class="btn small danger">Hapus</button>
                </form>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
