<?= $this->extend('/dashboard_template') ?>

<?= $this->section('content') ?>
<h3>Tambah Course</h3>
<form method="post" action="/admin/courses/save">
    <div class="mb-3">
        <label>Nama Course</label>
        <input type="text" name="course_name" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>Credits</label>
        <input type="number" name="credits" class="form-control" required>
    </div>
    <button type="submit" class="btn btn-success">Simpan</button>
</form>
<?= $this->endSection() ?>