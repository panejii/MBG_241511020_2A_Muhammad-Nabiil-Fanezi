<?= $this->extend('/dashboard_template'); ?>

<?= $this->section('content') ?>
<h3>Daftar Bahan Baku</h3>

<a href="/admin/bahan_baku/add" class="btn btn-primary mb-3">Tambah Bahan Baku</a>

<?php 
    $today = time(); 
?>

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
                <?php

                    $expiryDateTimestamp = strtotime($bahan['tanggal_kadaluarsa']);
                    $daysDifference = ceil(($expiryDateTimestamp - $today) / (60 * 60 * 24));
                    $dynamicStatus = 'Tersedia'; 
                                    
                    if ($bahan['jumlah'] <= 0) {
                        $dynamicStatus = 'HABIS';
                    } elseif ($daysDifference <= 0) {
                        $dynamicStatus = 'KADALUARSA';
                    } elseif ($daysDifference <= 3) {
                        $dynamicStatus = 'SEGERA KADALUARSA (H-' . $daysDifference . ')';
                    } else {
                        $dynamicStatus = 'Tersedia';
                    }
                ?>
                <tr>
                    <td><?= esc($bahan['nama']) ?></td>
                    <td><?= esc($bahan['kategori']) ?></td>
                    <td><?= esc($bahan['jumlah']) ?></td>
                    <td><?= esc($bahan['satuan']) ?></td>
                    <td><?= esc($bahan['tanggal_masuk']) ?></td>
                    <td><?= esc($bahan['tanggal_kadaluarsa']) ?></td>
                    <td><?= esc($dynamicStatus) ?></td>
                    
                    <?php
                    $deleteButtonClass = ($dynamicStatus === 'KADALUARSA') ? 'btn-danger' : 'btn-grey disabled';
                                $confirmMessage = ($dynamicStatus === 'KADALUARSA') 
                                    ? 'ANDA YAKIN INGIN MENGHAPUS BAHAN INI? HANYA BAHAN KADALUARSA YANG BOLEH DIHAPUS!'
                                    : 'PERINGATAN! Bahan ini belum kadaluarsa. Penghapusan akan ditolak Controller. Lanjutkan?';
                    ?>

                    <td class="text-nowrap">
                        <a href="/admin/bahan_baku/edit/<?= $bahan['id'] ?>" class="btn btn-warning btn-sm">Edit</a>
                        
                            <form action="/admin/bahan-baku/delete/<?= $bahan['id'] ?>" method="post" class="d-inline" onsubmit="return confirm('<?= esc($confirmMessage) ?>');">
                                    <?= csrf_field() ?>
                                    <button type="submit" class="btn <?= $deleteButtonClass ?> btn-sm">Hapus</button>
                                </form>
                       
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr><td colspan="8" class="text-center">Belum ada data bahan baku.</td></tr>
        <?php endif; ?>
    </tbody>
</table>
<?= $this->endSection() ?>
