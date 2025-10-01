
<?= $this->extend('/dashboard_template') ?>

<?= $this->section('content') ?>
<h3>Edit Student</h3>
<form method="post" action="/admin/students/update/<?= $student['student_id'] ?>">
    <div class="mb-3">
        <label>Username</label>
        <input type="text" name="username" class="form-control" value="<?= esc($student['username']) ?>" required>
    </div>
    <div class="mb-3">
        <label>Nama Lengkap</label>
        <input type="text" name="full_name" class="form-control" value="<?= esc($student['full_name']) ?>" required>
    </div>
    <div class="mb-3">
        <label>Tahun Masuk</label>
        <input type="number" name="entry_year" class="form-control" value="<?= esc($student['entry_year']) ?>" required>
    </div>
    <button type="submit" class="btn btn-success">Update</button>
</form>
<?= $this->endSection() ?>