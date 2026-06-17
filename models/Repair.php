<?php
class Repair {
    private $db;
    public function __construct($db) { $this->db = $db; }

    public function getAll() {
        $sql = "SELECT r.*, a.nama_aset, a.kode_aset 
                FROM repairs r 
                JOIN assets a ON r.asset_id = a.id 
                ORDER BY r.created_at DESC";
        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create($data) {
        $sql = "INSERT INTO repairs (asset_id, keluhan, status) VALUES (?, ?, 'Proses')";
        return $this->db->prepare($sql)->execute([$data['asset_id'], $data['keluhan']]);
    }

    public function updateStatus($id, $data) {
        $sql = "UPDATE repairs SET tindakan = ?, biaya = ?, status = ?, tanggal_selesai = CURRENT_DATE WHERE id = ?";
        return $this->db->prepare($sql)->execute([$data['tindakan'], $data['biaya'], $data['status'], $id]);
    }
}
?>
