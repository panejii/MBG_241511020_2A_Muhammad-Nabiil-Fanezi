<?= $this->extend('/dashboard_template'); ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <h3 class="mb-4">Tambah Bahan Baku Baru</h3>

    <!-- Tampilkan pesan sukses/error (Flashdata) -->
    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
    <?php endif; ?>

    <!-- Form menggunakan CodeIgniter 4 -->
    <form action="/admin/bahan_baku/store" method="post">
        <?= csrf_field() ?>
        
        <div class="card p-4 shadow">
            
            <div class="mb-3 row">
                <label for="nama" class="col-sm-3 col-form-label">Nama Bahan</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control" id="nama" name="nama" required value="<?= old('nama') ?>">
                </div>
            </div>

            <div class="mb-3 row">
                <label for="kategori" class="col-sm-3 col-form-label">Kategori</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control" id="kategori" name="kategori" required value="<?= old('kategori') ?>">
                </div>
            </div>
            
            <div class="mb-3 row">
                <label for="jumlah" class="col-sm-3 col-form-label">Jumlah Stok</label>
                <div class="col-sm-5">
                    <input type="number" class="form-control" id="jumlah" name="jumlah" required min="1" value="<?= old('jumlah') ?>">
                </div>
                <label for="satuan" class="col-sm-1 col-form-label">Satuan</label>
                <div class="col-sm-3">
                    <!-- Contoh pilihan satuan, Anda bisa ganti dengan input text jika lebih fleksibel -->
                    <select class="form-select" id="satuan" name="satuan" required>
                        <option value="kg" <?= old('satuan') == 'kg' ? 'selected' : '' ?>>Kilogram (kg)</option>
                        <option value="gr" <?= old('satuan') == 'gr' ? 'selected' : '' ?>>Gram (gr)</option>
                        <option value="pcs" <?= old('satuan') == 'pcs' ? 'selected' : '' ?>>Pcs</option>
                        <option value="ltr" <?= old('satuan') == 'ltr' ? 'selected' : '' ?>>Liter (ltr)</option>
                    </select>
                </div>
            </div>

            <div class="mb-3 row">
                <label for="tanggal_masuk" class="col-sm-3 col-form-label">Tanggal Masuk</label>
                <div class="col-sm-9">
                    <input type="date" class="form-control" id="tanggal_masuk" name="tanggal_masuk" required value="<?= old('tanggal_masuk', date('Y-m-d')) ?>">
                </div>
            </div>

            <div class="mb-3 row">
                <label for="tanggal_kadaluarsa" class="col-sm-3 col-form-label">Tanggal Kadaluarsa</label>
                <div class="col-sm-9">
                    <input type="date" class="form-control" id="tanggal_kadaluarsa" name="tanggal_kadaluarsa" required value="<?= old('tanggal_kadaluarsa') ?>">
                </div>
            </div>
            
            <div class="mb-3 row">
                <div class="col-sm-9 offset-sm-3">
                    <button type="submit" class="btn btn-primary">Simpan Bahan Baku</button>
                    <a href="/admin/bahan_baku" class="btn btn-secondary">Batal</a>
                </div>
            </div>
        </div>
    </form>
</div>
<?= $this->endSection() ?>
```

---

## 3. Routing: Menghubungkan *Controller*

Tambahkan *route group* baru di **`app/Config/Routes.php`** untuk *role* Admin (Gudang).

### File: `app/Config/Routes.php`

```php
// Tambahkan di bagian bawah file Routes.php
// Pastikan Anda juga memiliki route untuk 'dapur' yang sudah dibuat sebelumnya
$routes->group('admin', function($routes) {
    // URL untuk menampilkan form: [base_url]/admin/bahan-baku/add
    $routes->get('bahan-baku/add', 'Admin\BahanBakuController::add');
    
    // URL untuk memproses data: [base_url]/admin/bahan-baku/store (Method POST)
    $routes->post('bahan-baku/store', 'Admin\BahanBakuController::store');

    // Route untuk daftar bahan (untuk redirect setelah store)
    $routes->get('bahan-baku', 'Admin\BahanBakuController::index');
});
