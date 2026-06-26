<?php
require_once __DIR__ . '/../models/Repair.php';
require_once __DIR__ . '/../models/ActivityLog.php';

class RepairController {
    private $model;
    private $logModel;

    public function __construct($db) { 
        $this->model = new Repair($db); 
        $this->logModel = new ActivityLog($db);
    }

    public function index() { return $this->model->getAll(); }
    public function store($data) { 
        $result = $this->model->create($data); 
        if ($result) {
            $this->logModel->add($_SESSION['user_id'], 'LAPOR_RUSAK', "Melaporkan kerusakan aset ID: " . $data['asset_id']);
        }
        return $result;
    }
    public function update($id, $data) {
        // Fetch repair to get asset_id before updating
        $repair = $this->model->getAll(); // Note: This needs a better way to get a single repair if getAll doesn't filter
        // Actually, let's use the model's update directly.
        $result = $this->model->update($id, $data); 
        
        if ($result && $data['status'] === 'Selesai') {
            // Update asset condition to 'Baik'
            // Need to get the asset_id associated with this repair
            $repairDetails = $this->getRepairById($id); 
            if ($repairDetails) {
                require_once __DIR__ . '/../models/Asset.php';
                $assetModel = new Asset($this->db);
                $assetModel->update($repairDetails['asset_id'], ['kondisi' => 'Baik'], $_SESSION['user_id']);
            }
        }
        
        if ($result) {
            $this->logModel->add($_SESSION['user_id'], 'UPDATE_PERBAIKAN', "Update perbaikan ID: $id (" . $data['status'] . ")");
        }
        return $result;
    }

    // Helper to get repair by id, assuming needed to be added or exists
    private function getRepairById($id) {
        $repairs = $this->model->getAll();
        foreach ($repairs as $r) {
            if ($r['id'] == $id) return $r;
        }
        return null;
    }
}
?>
