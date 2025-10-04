<?= $this->extend('/dashboard_template'); ?>

<?= $this->section('content') ?>
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="mb-0">Proses Permintaan Bahan Baku (PRM-<?= $permintaan['id']; ?>)</h3>
        <a href="/admin/showPermintaan" class="btn btn-secondary">Kembali ke Daftar</a>
    </div>

    <div class="card p-4 shadow mb-4">
        <h5 class="card-title">Informasi Dasar Permintaan</h5>
        <div class="row">
            <div class="col-md-6">
                <p><strong>Pemohon:</strong> <?= $permintaan['pemohon_nama']; ?></p>
                <p><strong>Menu Masakan:</strong> <?= $permintaan['menu_makan']; ?></p>
            </div>
            <div class="col-md-6">
                <p><strong>Status:</strong> 
                    <span class="badge bg-warning text-dark"><?= ucfirst($permintaan['status']); ?></span>
                </p>
                <p><strong>Tgl. Masak:</strong> <?= date('d-m-Y', strtotime($permintaan['tgl_masak'])); ?></p>
            </div>
        </div>
    </div>

    <form action="/admin/permintaan/process/<?= $permintaan['id']; ?>" method="post">
        <?= csrf_field() ?>
        <input type="hidden" name="permintaan_id" value="<?= $permintaan['id']; ?>">

        <div class="card p-4 shadow mb-4">
            <h5 class="card-title">Proses Rincian Bahan Baku</h5>
            
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th style="width: 5%;">#</th>
                        <th>Nama Bahan Baku</th>
                        <th>Diminta</th>
                        <th>Stok Tersedia</th>
                        <th style="width: 25%;">Jumlah Diberi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($detail_bahan)): ?>
                        <tr>
                            <td colspan="5" class="text-center text-muted">Tidak ada rincian bahan baku.</td>
                        </tr>
                    <?php else: ?>
                        <?php $no = 1; foreach ($detail_bahan as $detail): ?>
                            <tr>
                                <td><?= $no++; ?></td>
                                <td><?= $detail['nama_bahan']; ?></td>
                                <td><?= $detail['jumlah_diminta']; ?> <?= $detail['satuan']; ?></td>
                                <td><span class="badge bg-secondary">T/A</span></td> 
                                <td>
                                    <input type="hidden" name="detail_id[]" value="<?= $detail['id']; ?>">
                                    <div class="input-group input-group-sm">
                                        <input type="number" 
                                               name="jumlah_diberi[]" 
                                               class="form-control" 
                                               value="<?= $detail['jumlah_diberi'] ?? 0; ?>" 
                                               min="0" 
                                               max="<?= $detail['jumlah_diminta']; ?>" 
                                               required 
                                               disabled> <span class="input-group-text"><?= $detail['satuan']; ?></span>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="text-end">
            <button type="button" class="btn btn-success btn-lg disabled">Setujui & Proses</button>
            <button type="button" class="btn btn-danger btn-lg disabled">Tolak Permintaan</button>
        </div>
    </form>
</div>
<?= $this->endSection() ?>