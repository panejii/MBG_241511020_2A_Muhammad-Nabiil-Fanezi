<?= $this->extend('/dashboard_template'); ?>

<?= $this->section('content') ?>
<h3>Daftar Bahan Baku</h3>

<a href="/admin/bahan-baku/add" class="btn btn-primary mb-3">Tambah Bahan Baku</a>

<table class="table table-bordered">
    <thead>
        <tr>
            <th>Nama Bahan</th>
            <th>Kategori</th>
            <th>Jumlah</th>
            <th>Satuan</th>
            <th>Tanggal Masuk</th>
            <th>Kadaluarsa</th>
            <th>Status</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($bahan_baku)): ?>
            <?php foreach ($bahan_baku as $bahan): ?>
                <tr>
                    <td><?= esc($bahan['nama']) ?></td>
                    <td><?= esc($bahan['kategori']) ?></td>
                    <td><?= esc($bahan['jumlah']) ?></td>
                    <td><?= esc($bahan['satuan']) ?></td>
                    <td><?= esc($bahan['tanggal_masuk']) ?></td>
                    <td><?= esc($bahan['tanggal_kadaluarsa']) ?></td>
                    <td><?= esc($bahan['status']) ?></td>
                    <td>
                        <a href="/admin/bahan-baku/edit/<?= $bahan['id'] ?>" class="btn btn-warning btn-sm">Edit</a>
                        <a href="/admin/bahan-baku/delete/<?= $bahan['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus bahan ini?')">Hapus</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr><td colspan="8" class="text-center">Belum ada data bahan baku.</td></tr>
        <?php endif; ?>
    </tbody>
</table>
<?= $this->endSection() ?>
