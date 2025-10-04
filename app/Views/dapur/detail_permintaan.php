<?= $this->extend('/dashboard_template'); ?>

<?= $this->section('content') ?>
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="mb-0">Detail Permintaan Bahan Baku</h3>
        <a href="/dapur/status_permintaan" class="btn btn-secondary">Kembali ke Daftar</a>
    </div>

    <div class="card p-4 shadow mb-4">
        <h5 class="card-title">Informasi Dasar Permintaan</h5>
        <div class="row">
            <div class="col-md-6">
                <p><strong>Status:</strong> 
                    <?php
                        $badge = 'bg-secondary';
                        if ($permintaan['status'] === 'menunggu') $badge = 'bg-warning text-dark';
                        if ($permintaan['status'] === 'disetujui') $badge = 'bg-success';
                        if ($permintaan['status'] === 'ditolak') $badge = 'bg-danger';
                    ?>
                    <span class="badge <?= $badge; ?>"><?= ucfirst($permintaan['status']); ?></span>
                </p>
                <p><strong>Menu Masakan:</strong> <?= $permintaan['menu_makan']; ?></p>
            </div>
            <div class="col-md-6">
                <p><strong>Tanggal Masak:</strong> <?= date('d-m-Y', strtotime($permintaan['tgl_masak'])); ?></p>
                <p><strong>Jumlah Porsi:</strong> <?= $permintaan['jumlah_porsi']; ?></p>
            </div>
        </div>
    </div>

    <div class="card p-4 shadow">
        <h5 class="card-title">Rincian Bahan Baku yang Diminta</h5>
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th style="width: 5%;">#</th>
                    <th>Nama Bahan Baku</th>
                    <th>Jumlah Diminta</th>
                    <th>Jumlah Disetujui (Gudang)</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($detail_bahan)): ?>
                    <tr>
                        <td colspan="4" class="text-center text-muted">Tidak ada rincian bahan baku yang ditemukan.</td>
                    </tr>
                <?php else: ?>
                    <?php $no = 1; foreach ($detail_bahan as $detail): ?>
                        <tr>
                            <td><?= $no++; ?></td>
                            <td><?= $detail['nama_bahan']; ?></td>
                            <td><?= $detail['jumlah_diminta']; ?> <?= $detail['satuan']; ?></td>
                            <td>
                                <?php if ($permintaan['status'] === 'disetujui' || $permintaan['status'] === 'ditolak'): ?>
                                    <span class="fw-bold"><?= $detail['jumlah_diberi'] ?? 0; ?> <?= $detail['satuan']; ?></span>
                                <?php else: ?>
                                    -
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

</div>
<?= $this->endSection() ?>