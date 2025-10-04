<?= $this->extend('/dashboard_template'); ?>

<?= $this->section('content') ?>
<div class="container">
    <h3 class="mb-4">Daftar Permintaan Bahan Baku Masuk</h3>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
    <?php endif; ?>

    <div class="card shadow p-3">
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th style="width: 5%;">#</th>
                    <th>ID Permintaan</th>
                    <th>Pemohon</th>
                    <th>Menu Masakan</th>
                    <th>Tgl. Masak</th>
                    <th>Status</th>
                    <th style="width: 20%;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($list_permintaan)): ?>
                    <tr>
                        <td colspan="7" class="text-center text-muted">Tidak ada permintaan bahan baku yang menunggu persetujuan.</td>
                    </tr>
                <?php else: ?>
                    <?php $no = 1; foreach ($list_permintaan as $permintaan): ?>
                        <tr>
                            <td><?= $no++; ?></td>
                            <td>PRM-<?= $permintaan['id']; ?></td>
                            <td><?= $permintaan['pemohon_nama']; ?></td> 
                            <td><?= $permintaan['menu_makan']; ?></td>
                            <td><?= date('d-m-Y', strtotime($permintaan['tgl_masak'])); ?></td>
                            <td>
                                <span class="badge bg-warning text-dark">Menunggu</span>
                            </td>
                            <td>
                                <a href="/admin/permintaan/detail/<?= $permintaan['id']; ?>" class="btn btn-info btn-sm me-2">Detail</a>
                                
                                <button class="btn btn-success btn-sm disabled">Setujui</button>
                                
                                <button class="btn btn-danger btn-sm disabled">Tolak</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?= $this->endSection() ?>