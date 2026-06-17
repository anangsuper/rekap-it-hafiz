<?php
class Maintenance {
    private $db;
    public function __construct($db) { $this->db = $db; }

    public function getAll() {
        $sql = "SELECT m.*, a.nama_aset, a.kode_aset 
                FROM maintenance m 
                JOIN assets a ON m.asset_id = a.id 
                ORDER BY m.tanggal DESC";
        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create($data) {
        $sql = "INSERT INTO maintenance (asset_id, tanggal, teknisi, keterangan) VALUES (?, ?, ?, ?)";
        return $this->db->prepare($sql)->execute([
            $data['asset_id'],
            $data['tanggal'],
            $data['teknisi'],
            $data['keterangan']
        ]);
    }
}
?>
