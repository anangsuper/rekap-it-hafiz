<?php
class Karyawan {
    private $conn;
    private $table = "karyawan";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getAll() {
        $query = "SELECT k.*, c.nama_cabang, d.nama_divisi 
                  FROM " . $this->table . " k
                  LEFT JOIN cabang c ON k.id_cabang = c.id
                  LEFT JOIN divisi d ON k.id_divisi = d.id
                  ORDER BY k.nama_karyawan ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function create($data) {
        $query = "INSERT INTO " . $this->table . " (nama_karyawan, nip, id_cabang, id_divisi, jabatan) 
                  VALUES (:nama_karyawan, :nip, :id_cabang, :id_divisi, :jabatan)";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute($data);
    }

    public function delete($id) {
        $stmt = $this->conn->prepare("DELETE FROM " . $this->table . " WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
?>