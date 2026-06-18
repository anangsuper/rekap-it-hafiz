<?php
class Maintenance {
    private $conn;
    private $table = "maintenance";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getAll() {
        $query = "SELECT m.*, a.nama_aset, a.kode_aset 
                  FROM " . $this->table . " m
                  JOIN assets a ON m.asset_id = a.id
                  ORDER BY m.tanggal DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function create($data) {
        $query = "INSERT INTO " . $this->table . " (asset_id, tanggal, teknisi, temuan, tindakan, rekomendasi, id_detail_jadwal) 
                  VALUES (:asset_id, :tanggal, :teknisi, :temuan, :tindakan, :rekomendasi, :id_detail_jadwal)";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute($data);
    }

    public function addPhoto($id_maintenance, $path, $tipe) {
        $query = "INSERT INTO foto_maintenance (id_maintenance, path_foto, tipe) VALUES (?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$id_maintenance, $path, $tipe]);
    }

    public function getPhotos($id_maintenance) {
        $query = "SELECT * FROM foto_maintenance WHERE id_maintenance = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$id_maintenance]);
        return $stmt->fetchAll();
    }
}
?>