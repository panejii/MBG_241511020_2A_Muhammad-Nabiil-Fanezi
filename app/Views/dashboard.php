
<?= $this->extend('/dashboard_template') ?>
<?= $this->section('content') ?>

<div class="container mt-4">
    <div class="card shadow-sm">
        <div class="card-body">
            <h2 class="card-title">Selamat datang di Dashboard <?= session()->get('role') ?>!</h2>
            <p class="card-text">Ini adalah konten dashboard untuk <?= session()->get('role'); ?>.</p>
            <?php if (session()->get('role') == 'dapur'): ?>
                Hello Dapur, <?= session()->get('name') ?>
            <?php else: ?>
                Hello Gudang, <?= session()->get('name') ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<?= $this->endSection() ?>