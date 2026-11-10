<?php
/**
 * View: CRUD tabel picks (punya FK game_id -> games)
 * Variabel $db sudah tersedia dari index.php
 */
$pick     = new Pick($db);
$game     = new Game($db);
$editData = null;

// ---- Proses aksi form (POST) ----
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        if (isset($_POST['create']) || isset($_POST['update'])) {
            $gameId     = (int) ($_POST['game_id'] ?? 0);
            $playerName = trim($_POST['player_name'] ?? '');
            $reason     = trim($_POST['reason'] ?? '');

            if ($gameId <= 0 || $playerName === '') {
                flash('error', 'Game dan nama pemilih wajib diisi.');
            } elseif (isset($_POST['create'])) {
                $pick->create($gameId, $playerName, $reason);
                flash('success', 'Pilihan game berhasil disimpan.');
            } else {
                $pick->update((int) $_POST['id'], $gameId, $playerName, $reason);
                flash('success', 'Pilihan game berhasil diperbarui.');
            }
        } elseif (isset($_POST['delete'])) {
            $pick->delete((int) $_POST['id']);
            flash('success', 'Pilihan game berhasil dihapus.');
        }
    } catch (PDOException $e) {
        flash('error', 'Terjadi kesalahan database: pastikan game yang dipilih valid.');
    }
    redirect('index.php?page=picks');
}

// ---- Mode edit ----
if (isset($_GET['edit'])) {
    $editData = $pick->getById((int) $_GET['edit']);
}

$rows  = $pick->getAll();
$games = $game->getAll(); // untuk dropdown
?>
<h2>Game Pilihanku</h2>

<form method="post" class="card form">
    <h3><?= $editData ? 'Edit Pilihan' : 'Pilih Game' ?></h3>
    <?php if ($editData): ?>
        <input type="hidden" name="id" value="<?= h($editData['id']) ?>">
    <?php endif; ?>

    <label>Nama Pemilih</label>
    <input type="text" name="player_name" maxlength="100" required value="<?= h($editData['player_name'] ?? '') ?>">

    <label>Game</label>
    <select name="game_id" required>
        <option value="">-- Pilih game --</option>
        <?php foreach ($games as $g): ?>
            <option value="<?= h($g['id']) ?>" <?= ($editData['game_id'] ?? '') == $g['id'] ? 'selected' : '' ?>>
                <?= h($g['title']) ?> (<?= h($g['genre_name']) ?>)
            </option>
        <?php endforeach; ?>
    </select>

    <label>Alasan Memilih</label>
    <input type="text" name="reason" maxlength="255" value="<?= h($editData['reason'] ?? '') ?>">

    <div class="actions">
        <?php if ($editData): ?>
            <button type="submit" name="update" class="btn primary">Simpan Perubahan</button>
            <a href="index.php?page=picks" class="btn">Batal</a>
        <?php else: ?>
            <button type="submit" name="create" class="btn primary">Simpan Pilihan</button>
        <?php endif; ?>
    </div>
</form>

<table class="card">
    <thead>
        <tr><th>ID</th><th>Pemilih</th><th>Game</th><th>Alasan</th><th>Tanggal</th><th>Aksi</th></tr>
    </thead>
    <tbody>
    <?php if (empty($rows)): ?>
        <tr><td colspan="6" class="empty">Belum ada pilihan game.</td></tr>
    <?php endif; ?>
    <?php foreach ($rows as $row): ?>
        <tr>
            <td><?= h($row['id']) ?></td>
            <td><?= h($row['player_name']) ?></td>
            <td><?= h($row['game_title']) ?></td>
            <td><?= h($row['reason']) ?></td>
            <td><?= h($row['picked_at']) ?></td>
            <td class="actions">
                <a href="index.php?page=picks&edit=<?= h($row['id']) ?>" class="btn small">Edit</a>
                <form method="post" onsubmit="return confirm('Hapus pilihan ini?')">
                    <input type="hidden" name="id" value="<?= h($row['id']) ?>">
                    <button type="submit" name="delete" class="btn small danger">Hapus</button>
                </form>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
