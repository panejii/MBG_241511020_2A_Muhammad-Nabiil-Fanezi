<?= $this->extend('/dashboard_template'); ?>

<?= $this->section('content') ?>
<div class="container">
    <h3 class="mb-4">Ajukan Permintaan Bahan Baku</h3>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
    <?php endif; ?>

    <form action="/dapur/permintaan/send" method="post" id="formPermintaan">
        <?= csrf_field() ?>
        
        <div class="card p-4 shadow mb-4">
            <h5 class="card-title">Informasi Dasar</h5>
            
            <div class="mb-3 row">
                <label for="tgl_masak" class="col-sm-3 col-form-label">Tanggal Rencana Masak</label>
                <div class="col-sm-9">
                    <input type="date" class="form-control" id="tgl_masak" name="tgl_masak" required 
                           value="<?= old('tgl_masak', date('Y-m-d')) ?>" min="<?= date('Y-m-d') ?>">
                </div>
            </div>

            <div class="mb-3 row">
                <label for="menu_makan" class="col-sm-3 col-form-label">Menu yang akan Dibuat</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control" id="menu_makan" name="menu_makan" required 
                           value="<?= old('menu_makan') ?>">
                </div>
            </div>

            <div class="mb-3 row">
                <label for="jumlah_porsi" class="col-sm-3 col-form-label">Jumlah Porsi yang Dibuat</label>
                <div class="col-sm-9">
                    <input type="number" class="form-control" id="jumlah_porsi" name="jumlah_porsi" required min="1" 
                           value="<?= old('jumlah_porsi') ?>">
                </div>
            </div>
        </div>

        <div class="card p-4 shadow mb-4">
            <h5 class="card-title">Daftar Bahan Baku yang Diminta</h5>
            
            <table class="table table-bordered" id="bahanDetailTable">
                <thead>
                    <tr>
                        <th style="width: 5%;">No</th>
                        <th style="width: 50%;">Nama Bahan Baku (Tersedia)</th>
                        <th style="width: 30%;">Jumlah Diminta</th>
                        <th style="width: 15%;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <tr id="noDataRow">
                        <td colspan="4" class="text-center text-muted">Belum ada bahan yang ditambahkan.</td>
                    </tr>
                </tbody>
            </table>
            
            <button type="button" class="btn btn-primary btn-sm mt-3" onclick="tambahBahan()">
                <i class="fas fa-plus"></i> Tambah Bahan
            </button>
        </div>

        <div class="text-end">
            <button type="submit" class="btn btn-success btn-lg">Ajukan Permintaan</button>
        </div>
    </form>
</div>

<script>
    let counter = 0;
    
    const availableBahan = <?= json_encode($bahan_tersedia ?? []); ?>; 
    const tableBody = document.getElementById('bahanDetailTable').getElementsByTagName('tbody')[0];
    const noDataRow = document.getElementById('noDataRow');


    function createBahanDropdown(id, selectedValue = '') {
        let options = '<option value="">-- Pilih Bahan --</option>';
        availableBahan.forEach(bahan => {
            const optionText = `${bahan.nama} (${bahan.jumlah} ${bahan.satuan} tersedia)`;
            const selected = bahan.id == selectedValue ? 'selected' : '';
            options += `<option value="${bahan.id}" ${selected}>${optionText}</option>`;
        });
        
        return `
            <!-- Ditambahkan kelas Bootstrap: form-select form-select-sm -->
            <select name="bahan_id[]" id="bahan_id_${id}" class="form-select form-select-sm" required>
                ${options}
            </select>
        `;
    }

    function tambahBahan(initialData = null) {
        const existingNoDataRow = document.getElementById('noDataRow');
        if (existingNoDataRow) {
            existingNoDataRow.remove();
        }

        counter++;
        let row = tableBody.insertRow();
        row.id = `row_${counter}`;
        
        row.insertCell(0).textContent = tableBody.rows.length; 
        let cellBahan = row.insertCell(1);
        cellBahan.innerHTML = createBahanDropdown(counter, initialData ? initialData.bahan_id : '');

        let cellJumlah = row.insertCell(2);
        cellJumlah.innerHTML = `
            <!-- Ditambahkan kelas Bootstrap: form-control form-control-sm -->
            <input type="number" name="jumlah_diminta[]" class="form-control form-control-sm" required min="1" 
                    value="${initialData ? initialData.jumlah_diminta : '1'}">
        `;

        let cellAksi = row.insertCell(3);
        cellAksi.innerHTML = `<button type="button" class="btn btn-danger btn-sm" onclick="hapusBahan(${counter})">Hapus</button>`;
        
        updateRowNumbers();
    }
    
    function hapusBahan(rowId) {
        document.getElementById(`row_${rowId}`).remove();
        updateRowNumbers();

        if (tableBody.rows.length === 0) {
            tableBody.innerHTML = '<tr id="noDataRow"><td colspan="4" class="text-center text-muted">Belum ada bahan yang ditambahkan.</td></tr>';
        }
    }

    function updateRowNumbers() {
        const rows = tableBody.rows;
        for (let i = 0; i < rows.length; i++) {
            rows[i].cells[0].textContent = i + 1; 
        }
    }
    
    window.onload = function() {
        const oldBahanIds = <?= json_encode(old('bahan_id') ?? []); ?>;
        
        if (oldBahanIds.length > 0) {
            const oldJumlahDiminta = <?= json_encode(old('jumlah_diminta') ?? []); ?>;
            
            const existingNoDataRow = document.getElementById('noDataRow');
            if (existingNoDataRow) {
                existingNoDataRow.remove();
            }

            oldBahanIds.forEach((bahanId, index) => {
                tambahBahan({
                    bahan_id: bahanId,
                    jumlah_diminta: oldJumlahDiminta[index]
                });
            });
        } else if (availableBahan.length > 0) {
             tambahBahan();
        } else {
             tableBody.innerHTML = '<tr id="noDataRow"><td colspan="4" class="text-center text-muted">Belum ada bahan yang ditambahkan.</td></tr>';
        }
    };
    
    if (document.readyState === 'complete') {
        window.onload();
    }
</script>
<?= $this->endSection() ?>
