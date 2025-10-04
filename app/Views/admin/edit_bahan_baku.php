<?= $this->extend('/dashboard_template'); ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <h3 class="mb-4">Update Stok Bahan Baku</h3>
    
    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
    <?php endif; ?>

    <div class="card p-4 shadow mb-4">
        <h5 class="card-title">Detail Bahan Baku: <?= esc($bahan['nama']) ?></h5>
        <hr>
        <div class="row">
            <div class="col-md-6">
                <p><strong>Kategori:</strong> <?= esc($bahan['kategori']) ?></p>
                <p><strong>Satuan:</strong> <?= esc($bahan['satuan']) ?></p>
                <p><strong>Tanggal Masuk:</strong> <?= esc($bahan['tanggal_masuk']) ?></p>
            </div>
            <div class="col-md-6">
                <p><strong>Stok Saat Ini:</strong> <span class="badge bg-info text-dark"><?= esc($bahan['jumlah']) ?></span> <?= esc($bahan['satuan']) ?></p>
                <p><strong>Kadaluarsa:</strong> <?= esc($bahan['tanggal_kadaluarsa']) ?></p>
                <p><strong>Status Bahan Baku:</strong> <?= esc($bahan['status']) ?></p>
            </div>
        </div>
    </div>

    <form action="/admin/bahan_baku/update/<?= esc($bahan['id']) ?>" method="post">
        <?= csrf_field() ?>
        <input type="hidden" name="_method" value="POST"> 
        
        <div class="card p-4 shadow">
            <h5 class="card-title">Form Perubahan Stok</h5>
            
            <div class="mb-3 row">
                <label for="jumlah" class="col-sm-3 col-form-label font-weight-bold">Jumlah Stok Baru</label>
                <div class="col-sm-6">
                    <input type="number" class="form-control" id="jumlah" name="jumlah" required min="0" value="<?= old('jumlah', $bahan['jumlah']) ?>">
                </div>
                <div class="col-sm-3 col-form-label"><?= esc($bahan['satuan']) ?></div>
            </div>

            <input type="hidden" name="nama" value="<?= esc($bahan['nama']) ?>">
            <input type="hidden" name="kategori" value="<?= esc($bahan['kategori']) ?>">
            <input type="hidden" name="satuan" value="<?= esc($bahan['satuan']) ?>">
            <input type="hidden" name="tanggal_masuk" value="<?= esc($bahan['tanggal_masuk']) ?>">
            <input type="hidden" name="tanggal_kadaluarsa" value="<?= esc($bahan['tanggal_kadaluarsa']) ?>">
            <input type="hidden" name="status" value="<?= esc($bahan['status']) ?>"> 

            <div class="mb-3 row mt-4">
                <div class="col-sm-9 offset-sm-3">
                    <button type="submit" class="btn btn-warning">Update Stok</button>
                    <a href="/admin/bahan_baku" class="btn btn-secondary">Kembali</a>
                </div>
            </div>
        </div>
    </form>
</div>
<?= $this->endSection() ?>
