<?= $this->extend('/dashboard_template'); ?>

<?= $this->section('content') ?>
<h3>Manage Students</h3>
<a href="/admin/students/add" class="btn btn-primary mb-3">Tambah Student</a>
<table class="table table-bordered">
    <thead>
        <tr>
            <th>Username</th>
            <th>Nama Lengkap</th>
            <th>Tahun Masuk</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($students as $student): ?>
        <tr>
            <td><?= esc($student['username']) ?></td>
            <td class="item-name"><?= esc($student['full_name'] ?? '-') ?></td>
            <td><?= esc($student['entry_year'] ?? '-') ?></td>
            <td>
                <a href="/admin/students/edit/<?= $student['student_id'] ?>" class="btn btn-warning btn-sm">Edit</a>
                <button class="btn btn-danger btn-sm delete-button" 
                        data-id="<?= $student['student_id'] ?>"
                        data-name="<?= esc($student['full_name'] ?? '-') ?>">
                    Hapus Student
                </button>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<div id="status-message" class="mt-3" style="display:none;"></div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const deleteButtons = document.querySelectorAll('.delete-button');
    const confirmDeleteButton = document.getElementById('confirm-delete-button');
    const itemNameSpan = document.getElementById('item-name');
    const confirmDeleteModal = new bootstrap.Modal(document.getElementById('confirmDeleteModal'));
    const statusMessageDiv = document.getElementById('status-message');
    
    let itemIdToDelete = null;

    function displayStatusMessage(message, type) {
        statusMessageDiv.textContent = message;
        statusMessageDiv.style.display = 'block';
        statusMessageDiv.className = `alert alert-${type}`;
    }

    deleteButtons.forEach(button => {
        button.addEventListener('click', function() {
            itemIdToDelete = this.dataset.id;
            const itemName = this.dataset.name;
            itemNameSpan.textContent = itemName;
            confirmDeleteModal.show();
        });
    });

    confirmDeleteButton.addEventListener('click', function() {
        confirmDeleteModal.hide();

        fetch(`/admin/students/delete/${itemIdToDelete}`, {
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
            displayStatusMessage('Terjadi kesalahan saat menghapus data.', 'danger');
        });
    });
});
</script>

<?= $this->endSection() ?>

