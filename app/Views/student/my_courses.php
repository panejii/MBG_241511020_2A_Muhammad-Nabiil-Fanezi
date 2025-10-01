<?= $this->extend('/dashboard_template') ?>

<?= $this->section('content') ?>

<h3>My Courses</h3>
<table class="table table-bordered">
    <thead>
        <tr>
            <th>Nama Course</th>
            <th>Credits</th>
            <th>Tanggal Enroll</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($mycourses as $course): ?>
        <tr>
            <td class="course-name"><?= esc($course['course_name']) ?></td>
            <td class="course-credits"><?= esc($course['credits']) ?></td>
            <td><?= esc($course['enroll_date']) ?></td>
            <td>
                <button class="btn btn-danger btn-sm unenroll-button" 
                        data-course-id="<?= $course['course_id'] ?>">
                    Batalkan
                </button>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<div id="status-message" class="mt-3" style="display:none;"></div>

<div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="confirmModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="confirmModalLabel">Konfirmasi Pembatalan</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        Yakin ingin membatalkan pendaftaran Course Mata Kuliah - <span id="modal-course-name"></span>?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
        <button type="button" class="btn btn-danger" id="confirm-unenroll">Ya, Batalkan</button>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const unenrollButtons = document.querySelectorAll('.unenroll-button');
        const statusMessageDiv = document.getElementById('status-message');
        const confirmModal = new bootstrap.Modal(document.getElementById('confirmModal'));
        const confirmUnenrollButton = document.getElementById('confirm-unenroll');
        const modalCourseNameSpan = document.getElementById('modal-course-name');
        let courseIdToUnenroll = null;

        function displayStatusMessage(message, type) {
            statusMessageDiv.textContent = message;
            statusMessageDiv.style.display = 'block';
            statusMessageDiv.className = `alert alert-${type}`;
        }

        unenrollButtons.forEach(button => {
            button.addEventListener('click', function() {
                // Ambil elemen <td> yang berisi nama course
                const row = this.closest('tr');
                const courseName = row.querySelector('.course-name').textContent;

                // Set nama course di dalam modal
                modalCourseNameSpan.textContent = courseName;
                courseIdToUnenroll = this.dataset.courseId;
                confirmModal.show();
            });
        });

        confirmUnenrollButton.addEventListener('click', function() {
            confirmModal.hide();
            fetch(`/student/courses/unenroll/${courseIdToUnenroll}`, {
                method: 'DELETE'
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    displayStatusMessage(data.message, 'success');
                    setTimeout(() => {
                        window.location.reload(); 
                    }, 2000); 
                } else {
                    displayStatusMessage(data.message, 'danger');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                displayStatusMessage('Terjadi kesalahan saat membatalkan pendaftaran.', 'danger');
            });
        });
    });
</script>

<?= $this->endSection() ?>

