<?php
require_once 'models/Karyawan.php';
require_once 'models/Cabang.php';
require_once 'models/Divisi.php';
require_once 'models/ActivityLog.php';

$karyawanModel = new Karyawan($conn);
$cabangModel = new Cabang($conn);
$divisiModel = new Divisi($conn);
$logModel = new ActivityLog($conn);

// Proses Hapus
if (isset($_POST['hapus'])) {
    $id = $_POST['id'];
    $currentKaryawan = null;
    // Find name for log
    foreach($karyawanModel->getAll() as $item) {
        if($item['id'] == $id) { $currentKaryawan = $item; break; }
    }
    
    if ($karyawanModel->delete($id)) {
        if($currentKaryawan) $logModel->add($_SESSION['user_id'], 'Hapus Karyawan', "Menghapus karyawan: " . $currentKaryawan['nama_karyawan']);
        header("Location: index.php?page=karyawan&status=deleted");
        exit();
    }
}

// Proses Tambah
$error_msg = "";
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['tambah'])) {
    $nip = trim($_POST['nip']);
    $data = [
        'nama_karyawan' => $_POST['nama_karyawan'],
        'nip' => !empty($nip) ? $nip : null,
        'id_cabang' => $_POST['id_cabang'],
        'id_divisi' => $_POST['id_divisi'],
        'jabatan' => $_POST['jabatan']
    ];

    if (!empty($data['nip']) && $karyawanModel->isNipExists($data['nip'])) {
        $error_msg = "Gagal! NIP [ " . $data['nip'] . " ] sudah terdaftar di sistem.";
    } else {
        if ($karyawanModel->create($data)) {
            $logModel->add($_SESSION['user_id'], 'Tambah Karyawan', "Menambahkan karyawan baru: " . $data['nama_karyawan']);
            header("Location: index.php?page=karyawan&status=success");
            exit();
        }
    }
}

// Proses Update
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update'])) {
    $id = $_POST['id'];
    $nip = trim($_POST['nip']);
    $data = [
        'nama_karyawan' => $_POST['nama_karyawan'],
        'nip' => !empty($nip) ? $nip : null,
        'id_cabang' => $_POST['id_cabang'],
        'id_divisi' => $_POST['id_divisi'],
        'jabatan' => $_POST['jabatan']
    ];

    if ($karyawanModel->update($id, $data)) {
        $logModel->add($_SESSION['user_id'], 'Update Karyawan', "Memperbarui data karyawan: " . $data['nama_karyawan']);
        header("Location: index.php?page=karyawan&status=updated");
        exit();
    }
}

$karyawans = $karyawanModel->getAll();
$cabangs = $cabangModel->getAll();
$divisis = $divisiModel->getAll();
?>

<div class="d-flex justify-content-between align-items-center mb-4 animate-fade-in">
    <div class="d-flex align-items-center">
        <div class="bg-primary bg-opacity-10 p-2 rounded-3 me-3 text-primary">
            <i class="bi bi-people fs-4"></i>
        </div>
        <div>
            <h4 class="fw-800 m-0">Employee Directory</h4>
            <p class="text-muted small m-0">Manage asset assignees and organization structure</p>
        </div>
    </div>
    <button class="btn btn-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#modalTambah">
        <i class="bi bi-person-plus me-2"></i> Add Employee
    </button>
</div>

<?php if (isset($_GET['status'])): ?>
    <?php if ($_GET['status'] == 'success'): ?>
        <div class="alert alert-success alert-dismissible fade show mb-4 border-0 shadow-sm rounded-4" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> Karyawan berhasil ditambahkan!
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php elseif ($_GET['status'] == 'updated'): ?>
        <div class="alert alert-info alert-dismissible fade show mb-4 border-0 shadow-sm rounded-4" role="alert">
            <i class="bi bi-info-circle-fill me-2"></i> Data karyawan berhasil diperbarui!
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php elseif ($_GET['status'] == 'deleted'): ?>
        <div class="alert alert-warning alert-dismissible fade show mb-4 border-0 shadow-sm rounded-4" role="alert">
            <i class="bi bi-trash-fill me-2"></i> Karyawan berhasil dihapus!
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
<?php endif; ?>

<?php if ($error_msg): ?>
    <div class="alert alert-danger alert-dismissible border-0 shadow-sm fade show mb-4 rounded-4" role="alert">
        <i class="bi bi-exclamation-octagon-fill me-2"></i> <?= $error_msg ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<div class="card border-0 shadow-sm animate-fade-in" style="animation-delay: 0.1s;">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th class="ps-4">Employee Info</th>
                        <th>NIP</th>
                        <th>Location</th>
                        <th>Position</th>
                        <th class="text-end pe-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(empty($karyawans)): ?>
                        <tr><td colspan="5" class="text-center py-5 text-muted">No employees registered yet.</td></tr>
                    <?php endif; ?>
                    <?php foreach ($karyawans as $k): ?>
                    <tr>
                        <td class="ps-4">
                            <div class="d-flex align-items-center">
                                <img src="https://ui-avatars.com/api/?name=<?= $k['nama_karyawan'] ?>&background=random&size=40" class="rounded-circle me-3">
                                <div class="fw-bold"><?= $k['nama_karyawan'] ?></div>
                            </div>
                        </td>
                        <td>
                            <code class="text-primary fw-bold"><?= $k['nip'] ?: '-' ?></code>
                        </td>
                        <td>
                            <div class="small fw-bold"><?= $k['nama_cabang'] ?></div>
                            <div class="small text-muted" style="font-size: 0.7rem;"><?= $k['nama_divisi'] ?></div>
                        </td>
                        <td>
                            <span class="small fw-500"><?= $k['jabatan'] ?: 'Staff' ?></span>
                        </td>
                        <td class="text-end pe-4">
                            <button class="btn btn-light btn-sm rounded-circle btn-edit" 
                                    data-id="<?= $k['id'] ?>" 
                                    data-nama="<?= $k['nama_karyawan'] ?>"
                                    data-nip="<?= $k['nip'] ?>"
                                    data-cabang="<?= $k['id_cabang'] ?>"
                                    data-divisi="<?= $k['id_divisi'] ?>"
                                    data-jabatan="<?= $k['jabatan'] ?>">
                                <i class="bi bi-pencil"></i>
                            </button>
                            <form method="POST" class="d-inline" onsubmit="return confirm('Hapus karyawan ini?')">
                                <input type="hidden" name="id" value="<?= $k['id'] ?>">
                                <button type="submit" name="hapus" class="btn btn-light btn-sm rounded-circle text-danger ms-1">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Tambah -->
<div class="modal fade" id="modalTambah" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 28px;">
            <form method="POST">
                <div class="modal-header border-0 p-4 pb-0">
                    <h5 class="fw-800 m-0"><i class="bi bi-person-plus-fill text-primary me-2"></i> Register New Employee</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Full Name</label>
                        <input type="text" name="nama_karyawan" class="form-control shadow-sm" placeholder="e.g. John Doe" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">NIP (Employee ID)</label>
                        <input type="text" name="nip" class="form-control shadow-sm" placeholder="Leave blank if not available">
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col-6">
                            <label class="form-label small fw-bold">Branch</label>
                            <select name="id_cabang" class="form-select shadow-sm" required>
                                <?php foreach ($cabangs as $c): ?>
                                    <option value="<?= $c['id'] ?>"><?= $c['nama_cabang'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="form-label small fw-bold">Division</label>
                            <select name="id_divisi" class="form-select shadow-sm" required>
                                <?php foreach ($divisis as $d): ?>
                                    <option value="<?= $d['id'] ?>"><?= $d['nama_divisi'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="mb-0">
                        <label class="form-label small fw-bold">Job Title / Position</label>
                        <input type="text" name="jabatan" class="form-control shadow-sm" placeholder="e.g. IT Support">
                    </div>
                </div>
                <div class="modal-footer border-0 p-4">
                    <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal" style="border-radius: 12px;">Cancel</button>
                    <button type="submit" name="tambah" class="btn btn-primary px-4">Save Employee</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Edit -->
<div class="modal fade" id="modalEdit" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 28px;">
            <form method="POST">
                <input type="hidden" name="id" id="edit_id">
                <div class="modal-header border-0 p-4 pb-0">
                    <h5 class="fw-800 m-0"><i class="bi bi-pencil-square text-warning me-2"></i> Update Employee Info</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Full Name</label>
                        <input type="text" name="nama_karyawan" id="edit_nama" class="form-control shadow-sm" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">NIP (Employee ID)</label>
                        <input type="text" name="nip" id="edit_nip" class="form-control shadow-sm">
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col-6">
                            <label class="form-label small fw-bold">Branch</label>
                            <select name="id_cabang" id="edit_cabang" class="form-select shadow-sm" required>
                                <?php foreach ($cabangs as $c): ?>
                                    <option value="<?= $c['id'] ?>"><?= $c['nama_cabang'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="form-label small fw-bold">Division</label>
                            <select name="id_divisi" id="edit_divisi" class="form-select shadow-sm" required>
                                <?php foreach ($divisis as $d): ?>
                                    <option value="<?= $d['id'] ?>"><?= $d['nama_divisi'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="mb-0">
                        <label class="form-label small fw-bold">Job Title / Position</label>
                        <input type="text" name="jabatan" id="edit_jabatan" class="form-control shadow-sm">
                    </div>
                </div>
                <div class="modal-footer border-0 p-4">
                    <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal" style="border-radius: 12px;">Cancel</button>
                    <button type="submit" name="update" class="btn btn-warning px-4">Update Employee</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.querySelectorAll('.btn-edit').forEach(btn => {
    btn.addEventListener('click', function() {
        const id = this.getAttribute('data-id');
        const nama = this.getAttribute('data-nama');
        const nip = this.getAttribute('data-nip');
        const cabang = this.getAttribute('data-cabang');
        const divisi = this.getAttribute('data-divisi');
        const jabatan = this.getAttribute('data-jabatan');

        document.getElementById('edit_id').value = id;
        document.getElementById('edit_nama').value = nama;
        document.getElementById('edit_nip').value = nip;
        document.getElementById('edit_cabang').value = cabang;
        document.getElementById('edit_divisi').value = divisi;
        document.getElementById('edit_jabatan').value = jabatan;

        new bootstrap.Modal(document.getElementById('modalEdit')).show();
    });
});
</script>

<style>
    .fw-800 { font-weight: 800; }
    .fw-500 { font-weight: 500; }
</style>
