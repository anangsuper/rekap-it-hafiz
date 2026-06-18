<?php
require_once 'models/Asset.php';
require_once 'models/KategoriAset.php';
require_once 'models/Cabang.php';
require_once 'models/Divisi.php';
require_once 'models/Karyawan.php';

$assetModel = new Asset($conn);
$kategoriModel = new KategoriAset($conn);
$cabangModel = new Cabang($conn);
$divisiModel = new Divisi($conn);
$karyawanModel = new Karyawan($conn);

$id_cabang_filter = isset($_GET['filter_cabang']) ? $_GET['filter_cabang'] : null;

$assets = $assetModel->getAll($id_cabang_filter);
$kategoris = $kategoriModel->getAll();
$cabangs = $cabangModel->getAll();
$divisis = $divisiModel->getAll();
$karyawans = $karyawanModel->getAll();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['tambah'])) {
    $data = [
        'kode_aset' => $_POST['kode_aset'],
        'nama_aset' => $_POST['nama_aset'],
        'serial_number' => $_POST['serial_number'],
        'id_kategori' => $_POST['id_kategori'],
        'merk' => $_POST['merk'],
        'model' => $_POST['model'],
        'id_cabang' => $_POST['id_cabang'],
        'id_divisi' => $_POST['id_divisi'],
        'id_karyawan' => $_POST['id_karyawan'],
        'kondisi' => $_POST['kondisi']
    ];
    if ($assetModel->create($data)) {
        header("Location: index.php?page=inventaris&status=success");
        exit();
    }
}
?>

<div class="d-flex justify-content-between align-items-center mb-4 animate-fade-in">
    <div class="d-flex align-items-center">
        <div class="bg-primary bg-opacity-10 p-2 rounded-3 me-3 text-primary">
            <i class="bi bi-laptop fs-4"></i>
        </div>
        <div>
            <h4 class="fw-800 m-0">Asset Inventory</h4>
            <p class="text-muted small m-0">Manage and track company hardware</p>
        </div>
    </div>
    <div class="d-flex align-items-center">
        <form method="GET" action="index.php" class="me-3">
            <input type="hidden" name="page" value="inventaris">
            <div class="input-group input-group-sm shadow-sm" style="width: 250px;">
                <span class="input-group-text bg-white border-end-0"><i class="bi bi-filter text-primary"></i></span>
                <select name="filter_cabang" class="form-select border-start-0" onchange="this.form.submit()">
                    <option value="">All Branches</option>
                    <?php foreach ($cabangs as $c): ?>
                        <option value="<?= $c['id'] ?>" <?= ($id_cabang_filter == $c['id']) ? 'selected' : '' ?>>
                            <?= $c['nama_cabang'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </form>
        <button class="btn btn-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#modalTambah">
            <i class="bi bi-plus-lg me-2"></i> Add Asset
        </button>
    </div>
</div>

<div class="card border-0 shadow-sm animate-fade-in" style="animation-delay: 0.1s;">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th class="ps-4">Asset Code</th>
                        <th>Device Details</th>
                        <th>Location</th>
                        <th>Assignee</th>
                        <th>Condition</th>
                        <th class="text-end pe-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(empty($assets)): ?>
                        <tr><td colspan="6" class="text-center py-5 text-muted">No assets found matching the criteria.</td></tr>
                    <?php endif; ?>
                    <?php foreach ($assets as $a): ?>
                    <tr>
                        <td class="ps-4">
                            <span class="fw-bold text-primary"><?= $a['kode_aset'] ?></span>
                            <div class="small text-muted" style="font-size: 0.65rem;">SN: <?= $a['serial_number'] ?: 'N/A' ?></div>
                        </td>
                        <td>
                            <div class="fw-bold"><?= $a['nama_aset'] ?></div>
                            <div class="small text-muted"><?= $a['merk'] ?> <?= $a['model'] ?> • <span class="text-dark opacity-75"><?= $a['nama_kategori'] ?></span></div>
                        </td>
                        <td>
                            <div class="small fw-bold"><?= $a['nama_cabang'] ?></div>
                            <div class="small text-muted" style="font-size: 0.7rem;"><?= $a['nama_divisi'] ?></div>
                        </td>
                        <td>
                            <?php if($a['nama_karyawan']): ?>
                                <div class="d-flex align-items-center">
                                    <img src="https://ui-avatars.com/api/?name=<?= $a['nama_karyawan'] ?>&background=random&size=24" class="rounded-circle me-2">
                                    <span class="small fw-500"><?= $a['nama_karyawan'] ?></span>
                                </div>
                            <?php else: ?>
                                <span class="badge bg-light text-muted fw-normal">Unassigned</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php 
                            $bg = 'success';
                            if ($a['kondisi'] == 'Rusak Ringan') $bg = 'warning';
                            if ($a['kondisi'] == 'Rusak Berat') $bg = 'danger';
                            ?>
                            <span class="badge bg-<?= $bg ?> bg-opacity-10 text-<?= $bg ?> rounded-pill px-3 py-2" style="font-size: 0.65rem;">
                                <i class="bi bi-circle-fill me-1" style="font-size: 0.4rem; vertical-align: middle;"></i> <?= strtoupper($a['kondisi']) ?>
                            </span>
                        </td>
                        <td class="text-end pe-4">
                            <div class="dropdown">
                                <button class="btn btn-light btn-sm rounded-circle" data-bs-toggle="dropdown">
                                    <i class="bi bi-three-dots-vertical"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0">
                                    <li><a class="dropdown-item py-2" href="#"><i class="bi bi-eye me-2 text-primary"></i> View Details</a></li>
                                    <li><a class="dropdown-item py-2" href="#"><i class="bi bi-pencil me-2 text-warning"></i> Edit Asset</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item py-2 text-danger" href="#"><i class="bi bi-trash me-2"></i> Delete</a></li>
                                </ul>
                            </div>
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
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 28px;">
            <form method="POST">
                <div class="modal-header border-0 p-4 pb-0">
                    <h5 class="fw-800 m-0"><i class="bi bi-plus-circle-fill text-primary me-2"></i> New Asset Registration</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <p class="text-muted small mb-4">Please provide the complete technical specifications and assignment details for the new asset.</p>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Asset Code</label>
                            <input type="text" name="kode_aset" class="form-control" placeholder="AST-001" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Serial Number</label>
                            <input type="text" name="serial_number" class="form-control" placeholder="SN12345678">
                        </div>
                        <div class="col-md-12">
                            <label class="form-label small fw-bold">Asset Name</label>
                            <input type="text" name="nama_aset" class="form-control" placeholder="ThinkPad X1 Carbon" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold">Category</label>
                            <select name="id_kategori" class="form-select">
                                <?php foreach ($kategoris as $k): ?>
                                    <option value="<?= $k['id'] ?>"><?= $k['nama_kategori'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold">Brand</label>
                            <input type="text" name="merk" class="form-control" placeholder="Lenovo">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold">Model</label>
                            <input type="text" name="model" class="form-control" placeholder="2024 Gen 11">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Branch</label>
                            <select name="id_cabang" id="select_cabang" class="form-select" required>
                                <option value="">-- Select Branch --</option>
                                <?php foreach ($cabangs as $c): ?>
                                    <option value="<?= $c['id'] ?>"><?= $c['nama_cabang'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Division</label>
                            <select name="id_divisi" class="form-select">
                                <option value="">-- Select Division --</option>
                                <?php foreach ($divisis as $d): ?>
                                    <option value="<?= $d['id'] ?>"><?= $d['nama_divisi'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Assign To</label>
                            <select name="id_karyawan" id="select_karyawan" class="form-select">
                                <option value="">-- Unassigned --</option>
                                <?php foreach ($karyawans as $kr): ?>
                                    <option value="<?= $kr['id'] ?>" data-cabang="<?= $kr['id_cabang'] ?>"><?= $kr['nama_karyawan'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Condition</label>
                            <select name="kondisi" class="form-select">
                                <option value="Baik">Baik (Excellent)</option>
                                <option value="Rusak Ringan">Rusak Ringan (Minor Issue)</option>
                                <option value="Rusak Berat">Rusak Berat (Major Issue)</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 p-4">
                    <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal" style="border-radius: 12px;">Cancel</button>
                    <button type="submit" name="tambah" class="btn btn-primary px-4">Register Asset</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.getElementById('select_cabang').addEventListener('change', function() {
    const selectedCabangId = this.value;
    const selectKaryawan = document.getElementById('select_karyawan');
    const options = selectKaryawan.querySelectorAll('option');

    options.forEach(option => {
        const cabangId = option.getAttribute('data-cabang');
        if (!cabangId) {
            option.style.display = 'block';
        } else {
            option.style.display = (cabangId === selectedCabangId) ? 'block' : 'none';
        }
    });
    selectKaryawan.value = "";
});
</script>

<style>
    .fw-800 { font-weight: 800; }
    .fw-500 { font-weight: 500; }
    .dropdown-item i { width: 20px; }
    .cursor-pointer { cursor: pointer; }
</style>
