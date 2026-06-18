<?php
class Repair {
    private $conn;
    private $table = "repairs";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getAll() {
        $query = "SELECT r.*, a.nama_aset, a.kode_aset 
                  FROM " . $this->table . " r
                  JOIN assets a ON r.asset_id = a.id
                  ORDER BY r.created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function create($data) {
        $query = "INSERT INTO " . $this->table . " (asset_id, keluhan, status) 
                  VALUES (:asset_id, :keluhan, 'Proses')";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute($data);
    }

    public function update($id, $data) {
        $sets = "";
        foreach ($data as $key => $value) {
            $sets .= "$key = :$key, ";
        }
        $sets = rtrim($sets, ", ");
        $query = "UPDATE " . $this->table . " SET $sets WHERE id = :id";
        $data['id'] = $id;
        $stmt = $this->conn->prepare($query);
        return $stmt->execute($data);
    }

    public function addSparepart($id_repair, $id_sparepart, $jumlah) {
        $query = "INSERT INTO penggunaan_sparepart (id_repair, id_sparepart, jumlah) VALUES (?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$id_repair, $id_sparepart, $jumlah]);
    }

    public function getSpareparts($id_repair) {
        $query = "SELECT ps.*, s.nama_sparepart 
                  FROM penggunaan_sparepart ps
                  JOIN sparepart s ON ps.id_sparepart = s.id
                  WHERE ps.id_repair = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$id_repair]);
        return $stmt->fetchAll();
    }
}
?>