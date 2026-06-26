<?php
require_once __DIR__ . '/../models/Repair.php';
require_once __DIR__ . '/../models/ActivityLog.php';

class RepairController {
    private $model;
    private $logModel;
    private $db; // Add this property

    public function __construct($db) { 
        $this->db = $db; // Store the db connection
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
        $repairDetails = $this->model->getById($id);
        
        $result = $this->model->update($id, $data); 
        
        if ($result && $data['status'] === 'Selesai' && $repairDetails) {
            // Update asset condition to 'Baik'
            require_once __DIR__ . '/../models/Asset.php';
            $assetModel = new Asset($this->db);
            $assetModel->update($repairDetails['asset_id'], ['kondisi' => 'Baik'], $_SESSION['user_id']);
        }
        
        if ($result) {
            $this->logModel->add($_SESSION['user_id'], 'UPDATE_PERBAIKAN', "Update perbaikan ID: $id (" . $data['status'] . ")");
        }
        return $result;
    }

    // getRepairById no longer needed if using getById directly
}
?>
