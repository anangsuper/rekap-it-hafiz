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
        $repairDetails = $this->model->getById($id);
        
        $result = $this->model->update($id, $data); 
        
        // JIKA STATUS JADI SELESAI, KITA UPDATE LANGSUNG KE DATABASE
        if ($result && $data['status'] === 'Selesai' && $repairDetails) {
            $assetId = $repairDetails['asset_id'];
            
            // SQL LANGSUNG TANPA MELEWATI LOGIKA MODEL LAIN
            $sql = "UPDATE assets SET kondisi = 'Baik' WHERE id = :asset_id";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['asset_id' => $assetId]);
            
            error_log("DEBUG: Update langsung kondisi aset $assetId ke Baik via SQL.");
        }
        
        if ($result) {
            $this->logModel->add($_SESSION['user_id'], 'UPDATE_PERBAIKAN', "Update perbaikan ID: $id (" . $data['status'] . ")");
        }
        return $result;
    }

    // getRepairById no longer needed if using getById directly
}
?>
