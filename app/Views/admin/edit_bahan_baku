
<?= $this->extend('/dashboard_template') ?>

<?= $this->section('content') ?>
<h3>Edit Course</h3>
<form method="post" action="/admin/courses/update/<?= $course['course_id'] ?>">
    <div class="mb-3">
        <label>Nama Course</label>
        <input type="text" name="course_name" class="form-control" value="<?= esc($course['course_name']) ?>" required>
    </div>
    <div class="mb-3">
        <label>Credits</label>
        <input type="number" name="credits" class="form-control" value="<?= esc($course['credits']) ?>" required>
    </div>
    <button type="submit" class="btn btn-success">Update</button>
</form>
<?= $this->endSection() ?>