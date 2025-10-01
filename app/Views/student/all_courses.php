<?= $this->extend('/dashboard_template') ?>

<?= $this->section('content') ?>

<script>
    console.log('Skrip di atas tabel berhasil!');
</script>

<h3>All Courses</h3>
<table class="table table-bordered">
    <thead>
        <tr>
            <th>Nama Course</th>
            <th>Credits</th>
            <th>Pilih</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($courses as $course): ?>
        <tr>
            <td><?= esc($course['course_name']) ?></td>
            <td class="course-credits"><?= esc($course['credits']) ?></td>
            <td>
                <?php
                $enrolled = false;
                foreach ($enrollments as $enroll) {
                    if ($enroll['course_id'] == $course['course_id']) {
                        $enrolled = true;
                        break;
                    }
                }
                ?>
                <?php if ($enrolled): ?>
                    <button class="btn btn-secondary btn-sm" disabled>Enrolled</button>
                <?php else: ?>
                    <input type="checkbox" class="course-checkbox" 
                           data-course-id="<?= $course['course_id'] ?>" 
                           data-credits="<?= $course['credits'] ?>">
                <?php endif; ?>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<div class="mt-3">
    <p>Total SKS yang ingin di Enroll: <span id="total-credits" class="fw-bold">0</span></p>
    <button id="enroll-button" class="btn btn-success">Enroll Courses</button>
</div>

<div id="status-message" class="mt-3" style="display:none;"></div>

<script>
const checkboxes = document.querySelectorAll('.course-checkbox');
        const totalCreditsSpan = document.getElementById('total-credits');
        const enrollButton = document.getElementById('enroll-button');
        const statusMessageDiv = document.getElementById('status-message');

        function updateTotalCredits() {
            let total = 0;
            checkboxes.forEach(checkbox => {
                if (checkbox.checked) {
                    total += parseInt(checkbox.dataset.credits);
                }
            });
            totalCreditsSpan.textContent = total;
        }

        function handleEnrollment(event) {
            event.preventDefault();

            const selectedCourseIds = [];
            checkboxes.forEach(checkbox => {
                if (checkbox.checked) {
                    selectedCourseIds.push(checkbox.dataset.courseId);
                }
            });

            if (selectedCourseIds.length === 0) {
                displayStatusMessage('Pilih setidaknya satu course untuk di-enroll.', 'danger');
                return;
            }

            // Buat objek FormData baru
            const formData = new FormData();
            selectedCourseIds.forEach(id => {
                formData.append('course_ids[]', id);
            });

            // Kirim data dengan fetch
            fetch('/student/courses/enroll', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json' // BARIS INI PENTING!
                },
                body: JSON.stringify({ // BARIS INI PENTING!
                    course_ids: selectedCourseIds 
                })
            })
            .then(response => response.json())
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
                displayStatusMessage('Terjadi kesalahan saat pendaftaran.', 'danger');
            });
        }
        
        function displayStatusMessage(message, type) {
            statusMessageDiv.textContent = message;
            statusMessageDiv.style.display = 'block';
            statusMessageDiv.className = `alert alert-${type}`;
        }
        
        checkboxes.forEach(checkbox => {
            checkbox.addEventListener('change', updateTotalCredits);
        });

        enrollButton.addEventListener('click', handleEnrollment);
</script>

<?= $this->endSection() ?>