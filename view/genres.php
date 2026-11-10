<?php
/**
 * View: CRUD tabel genres
 * Variabel $db sudah tersedia dari index.php
 */
$genre    = new Genre($db);
$editData = null;

// ---- Proses aksi form (POST) ----
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        if (isset($_POST['create']) || isset($_POST['update'])) {
            $name        = trim($_POST['name'] ?? '');
            $description = trim($_POST['description'] ?? '');

            if ($name === '') {
                flash('error', 'Nama genre wajib diisi.');
            } elseif (isset($_POST['create'])) {
                $genre->create($name, $description);
                flash('success', 'Genre berhasil ditambahkan.');
            } else {
                $genre->update((int) $_POST['id'], $name, $description);
                flash('success', 'Genre berhasil diperbarui.');
            }
        } elseif (isset($_POST['delete'])) {
            $genre->delete((int) $_POST['id']);
            flash('success', 'Genre berhasil dihapus.');
        }
    } catch (PDOException $e) {
        if ($e->getCode() === '23000') {
            flash('error', 'Gagal: nama genre sudah ada, atau genre masih dipakai oleh game.');
        } else {
            flash('error', 'Terjadi kesalahan database.');
        }
    }
    redirect('index.php?page=genres');
}

// ---- Mode edit: isi form dengan data lama ----
if (isset($_GET['edit'])) {
    $editData = $genre->getById((int) $_GET['edit']);
}

$rows = $genre->getAll();
?>
<h2>Data Genre</h2>

<form method="post" class="card form">
    <h3><?= $editData ? 'Edit Genre' : 'Tambah Genre' ?></h3>
    <?php if ($editData): ?>
        <input type="hidden" name="id" value="<?= h($editData['id']) ?>">
    <?php endif; ?>

    <label>Nama Genre</label>
    <input type="text" name="name" maxlength="100" required value="<?= h($editData['name'] ?? '') ?>">

    <label>Deskripsi</label>
    <input type="text" name="description" maxlength="255" value="<?= h($editData['description'] ?? '') ?>">

    <div class="actions">
        <?php if ($editData): ?>
            <button type="submit" name="update" class="btn primary">Simpan Perubahan</button>
            <a href="index.php?page=genres" class="btn">Batal</a>
        <?php else: ?>
            <button type="submit" name="create" class="btn primary">Tambah</button>
        <?php endif; ?>
    </div>
</form>

<table class="card">
    <thead>
        <tr><th>ID</th><th>Nama</th><th>Deskripsi</th><th>Aksi</th></tr>
    </thead>
    <tbody>
    <?php if (empty($rows)): ?>
        <tr><td colspan="4" class="empty">Belum ada data genre.</td></tr>
    <?php endif; ?>
    <?php foreach ($rows as $row): ?>
        <tr>
            <td><?= h($row['id']) ?></td>
            <td><?= h($row['name']) ?></td>
            <td><?= h($row['description']) ?></td>
            <td class="actions">
                <a href="index.php?page=genres&edit=<?= h($row['id']) ?>" class="btn small">Edit</a>
                <form method="post" onsubmit="return confirm('Hapus genre ini?')">
                    <input type="hidden" name="id" value="<?= h($row['id']) ?>">
                    <button type="submit" name="delete" class="btn small danger">Hapus</button>
                </form>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
