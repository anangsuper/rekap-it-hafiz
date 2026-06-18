<?php
class Asset {
    private $conn;
    private $table = "assets";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getAll() {
        $query = "SELECT a.*, k.nama_kategori, c.nama_cabang, d.nama_divisi, kr.nama_karyawan 
                  FROM " . $this->table . " a
                  LEFT JOIN kategori_aset k ON a.id_kategori = k.id
                  LEFT JOIN cabang c ON a.id_cabang = c.id
                  LEFT JOIN divisi d ON a.id_divisi = d.id
                  LEFT JOIN karyawan kr ON a.id_karyawan = kr.id
                  ORDER BY a.created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getById($id) {
        $query = "SELECT a.*, k.nama_kategori, c.nama_cabang, d.nama_divisi, kr.nama_karyawan 
                  FROM " . $this->table . " a
                  LEFT JOIN kategori_aset k ON a.id_kategori = k.id
                  LEFT JOIN cabang c ON a.id_cabang = c.id
                  LEFT JOIN divisi d ON a.id_divisi = d.id
                  LEFT JOIN karyawan kr ON a.id_karyawan = kr.id
                  WHERE a.id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function create($data) {
        $fields = implode(", ", array_keys($data));
        $placeholders = ":" . implode(", :", array_keys($data));
        $query = "INSERT INTO " . $this->table . " ($fields) VALUES ($placeholders)";
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

    public function delete($id) {
        $query = "DELETE FROM " . $this->table . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$id]);
    }

    public function getStats() {
        $stats = [];
        $stats['total'] = $this->conn->query("SELECT COUNT(*) FROM assets")->fetchColumn();
        $stats['baik'] = $this->conn->query("SELECT COUNT(*) FROM assets WHERE kondisi = 'Baik'")->fetchColumn();
        $stats['rusak_ringan'] = $this->conn->query("SELECT COUNT(*) FROM assets WHERE kondisi = 'Rusak Ringan'")->fetchColumn();
        $stats['rusak_berat'] = $this->conn->query("SELECT COUNT(*) FROM assets WHERE kondisi = 'Rusak Berat'")->fetchColumn();
        return $stats;
    }
}
?>