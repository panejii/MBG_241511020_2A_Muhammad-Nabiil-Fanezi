<?= $this->extend('/dashboard_template'); ?>

<?= $this->section('content') ?>
<div class="container">
    <h3 class="mb-4">Status Permintaan Bahan Baku</h3>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
    <?php endif; ?>

    <div class="card shadow p-3">
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th style="width: 5%;">#</th>
                    <th>ID Permintaan</th>
                    <th>Menu Masakan</th>
                    <th>Tgl. Rencana Masak</th>
                    <th>Jumlah Porsi</th>
                    <th>Status</th>
                    <th style="width: 15%;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($list_permintaan)): ?>
                    <tr>
                        <td colspan="7" class="text-center text-muted">Belum ada permintaan yang diajukan.</td>
                    </tr>
                <?php else: ?>
                    <?php $no = 1; foreach ($list_permintaan as $permintaan): ?>
                        <tr>
                            <td><?= $no++; ?></td>
                            <td>PRM-<?= $permintaan['id']; ?></td>
                            <td><?= $permintaan['menu_makan']; ?></td>
                            <td><?= date('d-m-Y', strtotime($permintaan['tgl_masak'])); ?></td>
                            <td><?= $permintaan['jumlah_porsi']; ?></td>
                            <td>
                                <?php
                                    $badge = 'bg-secondary';
                                    if ($permintaan['status'] === 'menunggu') $badge = 'bg-warning text-dark';
                                    if ($permintaan['status'] === 'disetujui') $badge = 'bg-success';
                                    if ($permintaan['status'] === 'ditolak') $badge = 'bg-danger';
                                ?>
                                <span class="badge <?= $badge; ?>"><?= ucfirst($permintaan['status']); ?></span>
                            </td>
                            <td>
                                <a href="/dapur/status_permintaan/detail/<?= $permintaan['id']; ?>" class="btn btn-info btn-sm">Detail</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?= $this->endSection() ?>