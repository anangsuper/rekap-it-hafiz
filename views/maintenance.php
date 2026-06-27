<?php
require_once 'controllers/MaintenanceController.php';
require_once 'models/Asset.php';
require_once 'models/Cabang.php';

$maintenanceController = new MaintenanceController($conn);
$assetModel = new Asset($conn);
$cabangModel = new Cabang($conn);

$sub = $_GET['sub'] ?? 'history';

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['tambah']) && $sub === 'history') {
        $data = [
            'asset_id' => $_POST['asset_id'],
            'tanggal' => $_POST['tanggal'],
            'teknisi' => $_POST['teknisi'],
            'temuan' => $_POST['temuan'],
            'tindakan' => $_POST['tindakan'],
            'rekomendasi' => $_POST['rekomendasi'],
            'status' => $_POST['status'],
            'id_detail_jadwal' => null
        ];
        if ($maintenanceController->store($data)) {
            header("Location: index.php?page=maintenance&status=success");
            exit();
        }
    } elseif (isset($_POST['proses_massal_final']) && $sub === 'massal') {
        $asset_ids = $_POST['asset_ids'] ?? [];
        $conn->beginTransaction();
        try {
            require_once 'models/Maintenance.php';
            $maintModel = new Maintenance($conn);
            foreach ($asset_ids as $id) {
                $data = [
                    'asset_id' => $id,
                    'tanggal' => $_POST['tanggal'][$id],
                    'teknisi' => $_POST['teknisi'][$id],
                    'temuan' => $_POST['temuan'][$id],
                    'tindakan' => $_POST['tindakan'][$id],
                    'rekomendasi' => $_POST['rekomendasi'][$id],
                    'status' => $_POST['status'][$id],
                    'id_detail_jadwal' => null
                ];
                $maintModel->create($data);
            }
            $conn->commit();
            header("Location: index.php?page=maintenance&sub=history&status=mass_success");
            exit();
        } catch (Exception $e) {
            $conn->rollBack();
            $error = "Gagal memproses maintenance massal: " . $e->getMessage();
        }
    }
}

// Prepare data
$maintenanceModel = new Maintenance($conn);
$maintenances = $maintenanceModel->getAll();
$assetsAvailable = $assetModel->getAssetsAvailableForMaintenance(date('m'), date('Y'));
$cabangs = $cabangModel->getAll();
$id_cabang = $_GET['id_cabang'] ?? '';
$assets = $id_cabang ? $assetModel->getAll($id_cabang) : [];
?>
<div class="d-flex justify-content-between align-items-center mb-4 animate-fade-in">
    <div>
        <h4 class="fw-800 m-0">Maintenance</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="index.php?page=maintenance&sub=history" class="text-decoration-none <?= $sub === 'history' ? 'fw-bold text-primary' : 'text-muted' ?>">History</a></li>
                <li class="breadcrumb-item"><a href="index.php?page=maintenance&sub=massal" class="text-decoration-none <?= $sub === 'massal' ? 'fw-bold text-primary' : 'text-muted' ?>">Massal</a></li>
            </ol>
        </nav>
    </div>
    <?php if ($sub === 'history'): ?>
    <button class="btn btn-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#modalTambah">
        <i class="bi bi-plus-lg me-2"></i> Log Check
    </button>
    <?php endif; ?>
</div>

<?php if ($sub === 'history'): ?>
<div class="card border-0 shadow-sm animate-fade-in overflow-hidden">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light border-bottom">
                    <tr>
                        <th class="ps-4" width="150">Tanggal</th>
                        <th width="220">Aset</th>
                        <th width="150">Teknisi</th>
                        <th width="200">Kondisi / Temuan</th>
                        <th>Tindakan & Rekomendasi</th>
                        <th class="text-end pe-4" width="100">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(empty($maintenances)): ?>
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <img src="https://cdn-icons-png.flaticon.com/512/7486/7486744.png" alt="No data" width="80" class="opacity-50 mb-3">
                                <p class="text-muted mb-0">Belum ada riwayat maintenance.</p>
                            </td>
                        </tr>
                    <?php endif; ?>
                    <?php foreach ($maintenances as $m): 
                        // Map status to classes
                        $status = $m['status'] ?? 'Baik';
                        if ($status === 'Baik') {
                            $badge_class = 'bg-success bg-opacity-10 text-success';
                            $status_icon = 'bi-check-circle-fill';
                        } elseif ($status === 'Perlu Perbaikan') {
                            $badge_class = 'bg-warning bg-opacity-10 text-warning';
                            $status_icon = 'bi-exclamation-triangle-fill';
                        } else {
                            $badge_class = 'bg-danger bg-opacity-10 text-danger';
                            $status_icon = 'bi-x-circle-fill';
                        }
                    ?>
                    <tr class="align-middle">
                        <td class="ps-4">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-calendar3 text-muted me-2"></i>
                                <span class="fw-semibold text-dark"><?= date('d M Y', strtotime($m['tanggal'])) ?></span>
                            </div>
                        </td>
                        <td>
                            <div class="fw-bold text-primary mb-0"><?= $m['kode_aset'] ?></div>
                            <div class="text-muted small text-truncate" style="max-width: 200px;" title="<?= $m['nama_aset'] ?>"><?= $m['nama_aset'] ?></div>
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="bg-secondary bg-opacity-10 text-secondary rounded-pill px-3 py-1 small fw-medium">
                                    <i class="bi bi-person-fill me-1"></i><?= $m['teknisi'] ?>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="d-flex flex-column gap-1">
                                <div>
                                    <span class="badge <?= $badge_class ?> rounded-pill px-2.5 py-1.5 fw-bold">
                                        <i class="bi <?= $status_icon ?> me-1"></i><?= $status ?>
                                    </span>
                                </div>
                                <?php if (!empty($m['temuan'])): ?>
                                    <small class="text-muted text-wrap" style="max-width: 180px;"><i class="bi bi-search me-1 small"></i><?= $m['temuan'] ?></small>
                                <?php endif; ?>
                            </div>
                        </td>
                        <td>
                            <div class="small">
                                <div class="text-dark fw-medium text-truncate mb-1" style="max-width: 300px;" title="<?= $m['tindakan'] ?>">
                                    <strong>Tindakan:</strong> <?= $m['tindakan'] ?: '<span class="text-muted">-</span>' ?>
                                </div>
                                <?php if (!empty($m['rekomendasi'])): ?>
                                <div class="text-muted text-truncate" style="max-width: 300px;" title="<?= $m['rekomendasi'] ?>">
                                    <strong>Rekomendasi:</strong> <?= $m['rekomendasi'] ?>
                                </div>
                                <?php endif; ?>
                            </div>
                        </td>
                        <td class="text-end pe-4">
                            <button type="button" class="btn btn-sm btn-light border btn-hover-primary" data-bs-toggle="collapse" data-bs-target="#detail<?= $m['id'] ?>">
                                <i class="bi bi-chevron-down"></i> Detail
                            </button>
                        </td>
                    </tr>
                    <tr class="collapse" id="detail<?= $m['id'] ?>">
                        <td colspan="6" class="bg-light p-4">
                            <div class="card border-0 shadow-sm rounded-3">
                                <div class="card-body">
                                    <h6 class="fw-bold text-dark border-bottom pb-2 mb-3"><i class="bi bi-info-circle text-primary me-2"></i>Rincian Lengkap Maintenance</h6>
                                    <div class="row g-3">
                                        <div class="col-md-3">
                                            <span class="text-muted small d-block">Aset & Kode</span>
                                            <span class="fw-bold text-dark"><?= $m['nama_aset'] ?> (<?= $m['kode_aset'] ?>)</span>
                                        </div>
                                        <div class="col-md-3">
                                            <span class="text-muted small d-block">Kondisi / Status</span>
                                            <span class="badge <?= $badge_class ?> rounded-pill px-2.5 py-1 fw-bold"><?= $status ?></span>
                                        </div>
                                        <div class="col-md-3">
                                            <span class="text-muted small d-block">Teknisi Pelaksana</span>
                                            <span class="fw-semibold text-dark"><?= $m['teknisi'] ?></span>
                                        </div>
                                        <div class="col-md-3 text-md-end">
                                            <span class="text-muted small d-block">Waktu Input</span>
                                            <span class="text-muted font-monospace small"><?= $m['created_at'] ?></span>
                                        </div>
                                        <div class="col-12 mt-3 pt-3 border-top">
                                            <div class="row">
                                                <div class="col-md-4 mb-2">
                                                    <strong class="d-block text-secondary small mb-1">Temuan Lapangan:</strong>
                                                    <div class="p-2.5 bg-light rounded text-dark small"><?= $m['temuan'] ?: 'Tidak ada temuan khusus' ?></div>
                                                </div>
                                                <div class="col-md-4 mb-2">
                                                    <strong class="d-block text-secondary small mb-1">Tindakan Perbaikan:</strong>
                                                    <div class="p-2.5 bg-light rounded text-dark small"><?= $m['tindakan'] ?: 'Tidak ada tindakan' ?></div>
                                                </div>
                                                <div class="col-md-4 mb-2">
                                                    <strong class="d-block text-secondary small mb-1">Rekomendasi Lanjutan:</strong>
                                                    <div class="p-2.5 bg-light rounded text-dark small"><?= $m['rekomendasi'] ?: 'Tidak ada rekomendasi' ?></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php else: ?>
<?php 
$stage = $_POST['stage'] ?? 'select';
$selected_ids = $_POST['asset_ids'] ?? [];
?>

<div class="animate-fade-in">
    <div class="card border-0 shadow-sm mb-4 overflow-hidden">
        <div class="card-header bg-white border-bottom-0 pt-4 pb-0 px-4">
            <h5 class="fw-bold mb-0 text-primary"><i class="bi bi-building me-2"></i>Pilih Cabang</h5>
            <p class="text-muted small mt-1">Pilih cabang untuk memuat daftar aset yang akan dimaintenance secara massal.</p>
        </div>
        <div class="card-body px-4 pb-4">
            <form method="GET" action="index.php">
                <input type="hidden" name="page" value="maintenance">
                <input type="hidden" name="sub" value="massal">
                <div class="row">
                    <div class="col-md-6 col-lg-5">
                        <div class="input-group input-group-lg shadow-sm rounded-3 overflow-hidden">
                            <span class="input-group-text bg-light border-0"><i class="bi bi-geo-alt-fill text-muted"></i></span>
                            <select name="id_cabang" class="form-select border-0 bg-light" onchange="this.form.submit()">
                                <option value="">-- Pilih Cabang --</option>
                                <?php foreach ($cabangs as $c): ?>
                                    <option value="<?= $c['id'] ?>" <?= ($id_cabang == $c['id']) ? 'selected' : '' ?>><?= $c['nama_cabang'] ?></option>
                                <?php endforeach; ?>
                            </select>
                            <button type="submit" class="btn btn-primary px-4 fw-bold">Muat Aset</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <form method="POST">
        <?php if ($id_cabang && $stage === 'select'): ?>
            <input type="hidden" name="stage" value="select">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom pt-4 pb-3 px-4 d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="fw-bold mb-0"><i class="bi bi-pc-display-horizontal text-primary me-2"></i>Daftar Aset / Komputer</h5>
                        <p class="text-muted small mt-1 mb-0">Pilih aset yang ingin di-maintenance secara massal.</p>
                    </div>
                    <div>
                        <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-3 py-2">
                            Total: <?= count($assets) ?> Aset
                        </span>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th width="60" class="text-center ps-3">
                                        <div class="form-check d-flex justify-content-center m-0">
                                            <input type="checkbox" id="checkAll" class="form-check-input" style="width: 1.2em; height: 1.2em;">
                                        </div>
                                    </th>
                                    <th>Kode Aset</th>
                                    <th>Nama Aset</th>
                                    <th class="text-end pe-4">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(empty($assets)): ?>
                                    <tr>
                                        <td colspan="4" class="text-center py-5">
                                            <img src="https://cdn-icons-png.flaticon.com/512/7486/7486744.png" alt="No data" width="80" class="opacity-50 mb-3">
                                            <p class="text-muted mb-0">Tidak ada aset ditemukan untuk cabang ini.</p>
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($assets as $a): ?>
                                    <tr>
                                        <td class="text-center ps-3">
                                            <div class="form-check d-flex justify-content-center m-0">
                                                <input type="checkbox" name="asset_ids[]" value="<?= $a['id'] ?>" class="form-check-input asset-checkbox" style="width: 1.2em; height: 1.2em;">
                                            </div>
                                        </td>
                                        <td>
                                            <span class="fw-bold text-dark"><?= $a['kode_aset'] ?></span>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="bg-primary bg-opacity-10 p-2 rounded-circle me-3">
                                                    <i class="bi bi-display text-primary"></i>
                                                </div>
                                                <span class="fw-medium"><?= $a['nama_aset'] ?></span>
                                            </div>
                                        </td>
                                        <td class="text-end pe-4">
                                            <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3">Tersedia</span>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <?php if(!empty($assets)): ?>
                <div class="card-footer bg-light border-0 p-4 text-end">
                    <button type="submit" name="stage" value="review" class="btn btn-primary px-5 py-2 fw-bold shadow-sm" id="btnNext" disabled>
                        Lanjut ke Edit Detail <i class="bi bi-arrow-right ms-2"></i>
                    </button>
                </div>
                <?php endif; ?>
            </div>
        <?php elseif ($stage === 'review'): ?>
            <input type="hidden" name="stage" value="review">
            <?php foreach ($selected_ids as $id): ?>
                <input type="hidden" name="asset_ids[]" value="<?= $id ?>">
            <?php endforeach; ?>
            
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="fw-bold mb-0"><i class="bi bi-pencil-square text-primary me-2"></i>Edit Detail Maintenance (<?= count($selected_ids) ?> Aset)</h5>
                <button type="button" class="btn btn-outline-secondary btn-sm" onclick="history.back()"><i class="bi bi-arrow-left me-1"></i> Kembali</button>
            </div>
            
            <div class="card p-4 mb-4 border-0 shadow-sm bg-primary bg-opacity-10 rounded-4">
                <div class="d-flex align-items-center mb-3">
                    <div class="bg-primary text-white rounded-circle p-2 d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                        <i class="bi bi-lightning-fill"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold text-primary mb-0">Terapkan Cepat ke Semua Aset</h6>
                        <small class="text-muted">Isi form ini untuk menyamakan data pada semua aset terpilih di bawah.</small>
                    </div>
                </div>
                <div class="row g-3 align-items-end">
                    <div class="col-md-2">
                        <label class="form-label small fw-bold text-muted mb-1">Tanggal</label>
                        <input type="date" id="all_tanggal" class="form-control form-control-sm border-0 shadow-sm" value="<?= date('Y-m-d') ?>">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small fw-bold text-muted mb-1">Teknisi</label>
                        <input type="text" id="all_teknisi" class="form-control form-control-sm border-0 shadow-sm" placeholder="Nama Teknisi">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small fw-bold text-muted mb-1">Kondisi</label>
                        <select id="all_status" class="form-select form-select-sm border-0 shadow-sm">
                            <option value="Baik">Baik</option>
                            <option value="Perlu Perbaikan">Perlu Perbaikan</option>
                            <option value="Rusak">Rusak</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small fw-bold text-muted mb-1">Temuan</label>
                        <input type="text" id="all_temuan" class="form-control form-control-sm border-0 shadow-sm" placeholder="Contoh: Kotor">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small fw-bold text-muted mb-1">Tindakan</label>
                        <input type="text" id="all_tindakan" class="form-control form-control-sm border-0 shadow-sm" placeholder="Contoh: Dibersihkan">
                    </div>
                    <div class="col-md-2">
                        <button type="button" class="btn btn-primary btn-sm w-100 shadow-sm" onclick="applyToAll()">
                            <i class="bi bi-check-all me-1"></i> Terapkan
                        </button>
                    </div>
                </div>
            </div>

            <div class="row g-4">
            <?php foreach ($selected_ids as $id): 
                $a = $assetModel->getById($id); ?>
                <div class="col-12">
                    <div class="card p-4 border-0 shadow-sm asset-row rounded-4 border-start border-primary border-4">
                        <div class="d-flex align-items-center mb-3 pb-3 border-bottom">
                            <div class="bg-light p-2 rounded-circle me-3">
                                <i class="bi bi-pc-display text-dark"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold mb-0"><?= $a['nama_aset'] ?></h6>
                                <small class="text-muted">Kode: <?= $a['kode_aset'] ?></small>
                            </div>
                        </div>
                        <input type="hidden" name="asset_ids[]" value="<?= $id ?>">
                        <div class="row g-3">
                            <div class="col-md-2">
                                <label class="form-label small fw-bold text-muted">Tanggal <span class="text-danger">*</span></label>
                                <input type="date" name="tanggal[<?= $id ?>]" class="form-control row-tanggal bg-light border-0" value="<?= date('Y-m-d') ?>" required>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label small fw-bold text-muted">Teknisi <span class="text-danger">*</span></label>
                                <input type="text" name="teknisi[<?= $id ?>]" class="form-control row-teknisi bg-light border-0" placeholder="Teknisi" required>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label small fw-bold text-muted">Kondisi <span class="text-danger">*</span></label>
                                <select name="status[<?= $id ?>]" class="form-select row-status bg-light border-0">
                                    <option value="Baik">Baik</option>
                                    <option value="Perlu Perbaikan">Perlu Perbaikan</option>
                                    <option value="Rusak">Rusak</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label small fw-bold text-muted">Temuan</label>
                                <input type="text" name="temuan[<?= $id ?>]" class="form-control row-temuan bg-light border-0" placeholder="Temuan">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label small fw-bold text-muted">Tindakan</label>
                                <input type="text" name="tindakan[<?= $id ?>]" class="form-control row-tindakan bg-light border-0" placeholder="Tindakan">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label small fw-bold text-muted">Rekomendasi</label>
                                <input type="text" name="rekomendasi[<?= $id ?>]" class="form-control row-rekomendasi bg-light border-0" placeholder="Rekomendasi">
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
            </div>
            
            <div class="mt-4 mb-5 text-end">
                <button type="submit" name="proses_massal_final" class="btn btn-success btn-lg px-5 py-3 fw-bold shadow-sm rounded-pill">
                    <i class="bi bi-save me-2"></i> Simpan Semua Maintenance
                </button>
            </div>

            <script>
                function applyToAll() {
                    const fields = ['tanggal', 'teknisi', 'status', 'temuan', 'tindakan'];
                    fields.forEach(field => {
                        const allVal = document.getElementById('all_' + field).value;
                        if (allVal) {
                            document.querySelectorAll('.row-' + field).forEach(el => el.value = allVal);
                        }
                    });
                }
            </script>
        <?php endif; ?>
    </form>
</div>

<script>
    const checkAll = document.getElementById('checkAll');
    const checkboxes = document.querySelectorAll('.asset-checkbox');
    const btnNext = document.getElementById('btnNext');

    if (checkAll) {
        checkAll.addEventListener('change', function() {
            checkboxes.forEach(cb => cb.checked = this.checked);
            if(btnNext) btnNext.disabled = !this.checked;
        });
    }

    checkboxes.forEach(cb => {
        cb.addEventListener('change', function() {
            const checkedCount = document.querySelectorAll('.asset-checkbox:checked').length;
            if(btnNext) btnNext.disabled = checkedCount === 0;
            if(checkAll) checkAll.checked = checkedCount === checkboxes.length && checkboxes.length > 0;
        });
    });
</script>
<?php endif; ?>
