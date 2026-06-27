<?php
require_once 'controllers/RepairController.php';
require_once 'models/Asset.php';
require_once 'models/Cabang.php';

$repairController = new RepairController($conn);
$assetModel = new Asset($conn);
$cabangModel = new Cabang($conn);

$repairs = $repairController->index();
$cabangs = $cabangModel->getAll();
$assets = $assetModel->getAll();
// ... (rest of PHP code remains)

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['tambah'])) {
    $data = [
        'asset_id' => $_POST['asset_id'],
        'keluhan' => $_POST['keluhan']
    ];
    if ($repairController->store($data)) {
        header("Location: index.php?page=perbaikan&status=success");
        exit();
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update'])) {
    $id = $_POST['id'];
    $data = [
        'tindakan' => $_POST['tindakan'],
        'biaya' => $_POST['biaya'],
        'status' => $_POST['status'],
        'tanggal_selesai' => ($_POST['status'] == 'Selesai') ? date('Y-m-d') : null
    ];
    if ($repairController->update($id, $data)) {
        header("Location: index.php?page=perbaikan&status=updated");
        exit();
    }
}
?>

<div class="d-flex justify-content-between align-items-center mb-4 animate-fade-in">
    <div class="d-flex align-items-center">
        <div class="bg-warning bg-opacity-10 p-2 rounded-3 me-3 text-warning">
            <i class="bi bi-wrench-adjustable fs-4"></i>
        </div>
        <div>
            <h4 class="fw-800 m-0">Manajemen Perbaikan</h4>
            <p class="text-muted small m-0">Lacak kerusakan aset dan biaya perbaikan</p>
        </div>
    </div>
    <button class="btn btn-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#modalTambah">
        <i class="bi bi-plus-lg me-2"></i> Tiket Baru
    </button>
</div>

<div class="card border-0 shadow-sm animate-fade-in" style="animation-delay: 0.1s;">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th class="ps-4">Aset</th>
                        <th>Deskripsi Kerusakan</th>
                        <th>Status</th>
                        <th>Biaya</th>
                        <th>Tanggal Lapor</th>
                        <th class="text-end pe-4">Tindakan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(empty($repairs)): ?>
                        <tr><td colspan="6" class="text-center py-5 text-muted">Belum ada tiket perbaikan ditemukan.</td></tr>
                    <?php endif; ?>
                    <?php foreach ($repairs as $r): ?>
                    <tr>
                        <td class="ps-4">
                            <div class="fw-bold text-primary"><?= $r['kode_aset'] ?></div>
                            <div class="small text-muted"><?= $r['nama_aset'] ?></div>
                        </td>
                        <td>
                            <div class="small fw-500 text-dark"><?= $r['keluhan'] ?></div>
                            <?php if($r['tindakan']): ?>
                                <div class="mt-1 small text-muted fst-italic">Sol: <?= $r['tindakan'] ?></div>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php 
                            $badge = 'warning';
                            $statusText = 'Dalam Proses';
                            if ($r['status'] == 'Selesai') { $badge = 'success'; $statusText = 'Selesai'; }
                            if ($r['status'] == 'Batal') { $badge = 'danger'; $statusText = 'Batal'; }
                            ?>
                            <span class="badge bg-<?= $badge ?> bg-opacity-10 text-<?= $badge ?> rounded-pill px-3 py-2" style="font-size: 0.65rem;">
                                <?= strtoupper($statusText) ?>
                            </span>
                        </td>
                        <td>
                            <div class="fw-bold text-dark">Rp <?= number_format($r['biaya'], 0, ',', '.') ?></div>
                        </td>
                        <td>
                            <div class="small text-muted"><?= date('d/m/Y', strtotime($r['created_at'])) ?></div>
                        </td>
                        <td class="text-end pe-4">
                            <button class="btn btn-light btn-sm rounded-3 btn-edit px-3" 
                                    data-id="<?= $r['id'] ?>" 
                                    data-aset="<?= $r['nama_aset'] ?>" 
                                    data-keluhan="<?= $r['keluhan'] ?>"
                                    data-tindakan="<?= $r['tindakan'] ?>"
                                    data-biaya="<?= $r['biaya'] ?>"
                                    data-status="<?= $r['status'] ?>"
                                    data-bs-toggle="modal" 
                                    data-bs-target="#modalUpdate">
                                <i class="bi bi-pencil-square me-1"></i> Perbarui
                            </button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Update -->
<div class="modal fade" id="modalUpdate" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 28px;">
            <form method="POST">
                <input type="hidden" name="id" id="update_id">
                <div class="modal-header border-0 p-4 pb-0">
                    <h5 class="fw-800 m-0"><i class="bi bi-pencil-fill text-primary me-2"></i> Perbarui Detail Perbaikan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="bg-light p-3 rounded-4 mb-4 border border-white shadow-sm">
                        <div class="small text-muted mb-1">Informasi Aset:</div>
                        <div class="fw-bold" id="update_aset_text"></div>
                        <div class="small text-danger mt-2" id="update_keluhan_text"></div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Solusi / Tindakan</label>
                        <textarea name="tindakan" id="update_tindakan" class="form-control" rows="3" placeholder="Jelaskan perbaikan yang dilakukan..." required></textarea>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Biaya Perbaikan (Rp)</label>
                            <input type="number" name="biaya" id="update_biaya" class="form-control" value="0">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Status Baru</label>
                            <select name="status" id="update_status" class="form-select">
                                <option value="Proses">Dalam Proses</option>
                                <option value="Selesai">Selesai (Sukses)</option>
                                <option value="Batal">Batal (Tidak Bisa)</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 p-4">
                    <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal" style="border-radius: 12px;">Batal</button>
                    <button type="submit" name="update" class="btn btn-primary px-4">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Tambah -->
<div class="modal fade" id="modalTambah" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 28px;">
            <form method="POST">
                <div class="modal-header border-0 p-4 pb-0">
                    <h5 class="fw-800 m-0"><i class="bi bi-plus-circle-fill text-primary me-2"></i> Buat Tiket Perbaikan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Pilih Cabang Aset</label>
                        <select id="branchSelect" class="form-select shadow-sm mb-2" onchange="filterAssetsByBranch()">
                            <option value="">Semua Cabang</option>
                            <?php foreach ($cabangs as $c): ?>
                                <option value="<?= $c['nama_cabang'] ?>"><?= $c['nama_cabang'] ?></option>
                            <?php endforeach; ?>
                        </select>
                        <label class="form-label small fw-bold mt-2">Pilih Aset Bermasalah</label>
                        <!-- Custom Searchable Dropdown with colored condition badges -->
                        <div class="dropdown custom-select-dropdown position-relative" id="assetDropdownContainer">
                            <input type="hidden" name="asset_id" id="selectedAssetId" required>
                            <button class="form-select text-start w-100 shadow-sm d-flex justify-content-between align-items-center" type="button" id="assetDropdownTrigger" data-bs-toggle="dropdown" aria-expanded="false" style="background-color: #fff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 10px 16px; min-height: 46px;">
                                <span class="text-muted" id="selectedAssetLabel">Pilih Aset Bermasalah...</span>
                            </button>
                            <div class="dropdown-menu w-100 p-3 shadow-lg border-0" aria-labelledby="assetDropdownTrigger" style="border-radius: 16px; max-height: 350px; overflow-y: auto; z-index: 1100; background: #fff;">
                                <div class="mb-3">
                                    <div class="input-group input-group-sm">
                                        <span class="input-group-text bg-light border-end-0"><i class="bi bi-search text-muted"></i></span>
                                        <input type="text" class="form-control border-start-0" id="assetSearchInput" placeholder="Cari kode, nama, pemegang, atau cabang..." onkeyup="filterCustomAssets()">
                                    </div>
                                </div>
                                <div class="custom-assets-list" style="max-height: 240px; overflow-y: auto;">
                                    <?php foreach ($assets as $a): ?>
                                        <?php
                                            $condColor = 'success';
                                            if ($a['kondisi'] == 'Rusak Ringan') $condColor = 'warning';
                                            if ($a['kondisi'] == 'Rusak Berat') $condColor = 'danger';
                                        ?>
                                        <button type="button" class="dropdown-item custom-asset-item d-flex justify-content-between align-items-center border-bottom py-2 px-2 text-start" 
                                                data-id="<?= $a['id'] ?>" 
                                                data-branch="<?= htmlspecialchars($a['nama_cabang']) ?>"
                                                data-search="<?= htmlspecialchars(strtolower($a['kode_aset'] . ' ' . $a['nama_aset'] . ' ' . ($a['nama_karyawan'] ?? 'Unassigned') . ' ' . $a['nama_cabang'])) ?>"
                                                onclick="selectCustomAsset(this)"
                                                style="border-radius: 8px; border: none; background: transparent; width: 100%; transition: background-color 0.2s;">
                                            <div>
                                                <div class="fw-bold text-dark mb-0" style="font-size: 0.85rem;">
                                                    <span class="badge bg-primary bg-opacity-10 text-primary me-1" style="font-size: 0.7rem; padding: 2px 6px;"><?= $a['kode_aset'] ?></span>
                                                    <?= htmlspecialchars($a['nama_aset']) ?>
                                                </div>
                                                <div class="text-muted" style="font-size: 0.7rem; margin-top: 2px;">
                                                    <i class="bi bi-person me-1"></i><?= htmlspecialchars($a['nama_karyawan'] ?? 'Unassigned') ?> &bull; 
                                                    <i class="bi bi-geo-alt me-1"></i><?= htmlspecialchars($a['nama_cabang']) ?>
                                                </div>
                                            </div>
                                            <span class="badge bg-<?= $condColor ?> bg-opacity-10 text-<?= $condColor ?> rounded-pill px-2 py-1" style="font-size: 0.65rem; font-weight: 700;">
                                                <?= strtoupper($a['kondisi']) ?>
                                            </span>
                                        </button>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mb-0">
                        <label class="form-label small fw-bold">Keluhan Pengguna / Info Kerusakan</label>
                        <textarea name="keluhan" class="form-control shadow-sm" rows="4" placeholder="Jelaskan kerusakan sedetail mungkin..." required></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0 p-4">
                    <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal" style="border-radius: 12px;">Batal</button>
                    <button type="submit" name="tambah" class="btn btn-primary px-4">Buat Tiket</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
function selectCustomAsset(element) {
    var id = element.getAttribute("data-id");
    // Clone selection text to display in button
    var labelHtml = element.querySelector("div").innerHTML;
    
    // Set values
    document.getElementById("selectedAssetId").value = id;
    document.getElementById("selectedAssetLabel").innerHTML = labelHtml;
    
    // Hide dropdown
    var dropdownTrigger = document.getElementById("assetDropdownTrigger");
    var dropdown = bootstrap.Dropdown.getInstance(dropdownTrigger);
    if (!dropdown) {
        dropdown = new bootstrap.Dropdown(dropdownTrigger);
    }
    dropdown.hide();
}

function filterCustomAssets() {
    var query = document.getElementById("assetSearchInput").value.toLowerCase();
    var items = document.querySelectorAll(".custom-asset-item");
    var selectedBranch = document.getElementById("branchSelect").value;
    
    items.forEach(function(item) {
        var searchText = item.getAttribute("data-search");
        var itemBranch = item.getAttribute("data-branch");
        
        var matchSearch = searchText.includes(query);
        var matchBranch = (selectedBranch === "" || itemBranch === selectedBranch);
        
        if (matchSearch && matchBranch) {
            item.style.setProperty("display", "flex", "important");
        } else {
            item.style.setProperty("display", "none", "important");
        }
    });
}

function filterAssetsByBranch() {
    // Reset search query
    document.getElementById("assetSearchInput").value = "";
    // Re-filter asset list
    filterCustomAssets();
    // Clear selection
    document.getElementById("selectedAssetId").value = "";
    document.getElementById("selectedAssetLabel").innerHTML = '<span class="text-muted">Pilih Aset Bermasalah...</span>';
}

document.querySelectorAll('.btn-edit').forEach(button => {
    button.addEventListener('click', function() {
        document.getElementById('update_id').value = this.getAttribute('data-id');
        document.getElementById('update_aset_text').innerText = this.getAttribute('data-aset');
        document.getElementById('update_keluhan_text').innerText = "Masalah: " + this.getAttribute('data-keluhan');
        document.getElementById('update_tindakan').value = this.getAttribute('data-tindakan') || '';
        document.getElementById('update_biaya').value = this.getAttribute('data-biaya') || 0;
        document.getElementById('update_status').value = this.getAttribute('data-status');
    });
});
</script>

<style>
    .fw-800 { font-weight: 800; }
    .fw-500 { font-weight: 500; }
    
    .custom-asset-item {
        border-bottom: 1px solid #f1f5f9 !important;
    }
    .custom-asset-item:last-child {
        border-bottom: none !important;
    }
    .custom-asset-item:hover {
        background-color: #f8fafc !important;
    }
</style>
