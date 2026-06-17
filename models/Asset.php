<?php

class Asset
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function getAll()
    {
        $stmt = $this->db->query(
            "SELECT * FROM assets ORDER BY created_at DESC"
        );

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create($data)
    {
        $sql = "INSERT INTO assets
                (kode_aset, nama_aset, spesifikasi, lokasi, kondisi, foto)
                VALUES (?, ?, ?, ?, ?, ?)";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            $data['kode'],
            $data['nama'],
            $data['spek'],
            $data['lokasi'],
            $data['kondisi'],
            $data['foto']
        ]);
    }

    public function delete($id)
    {
        $stmt = $this->db->prepare(
            "DELETE FROM assets WHERE id = ?"
        );

        return $stmt->execute([$id]);
    }

    public function getById($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM assets WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function update($id, $data)
    {
        $sql = "UPDATE assets SET 
                kode_aset = ?, 
                nama_aset = ?, 
                spesifikasi = ?, 
                lokasi = ?, 
                kondisi = ?, 
                foto = ? 
                WHERE id = ?";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            $data['kode'],
            $data['nama'],
            $data['spek'],
            $data['lokasi'],
            $data['kondisi'],
            $data['foto'],
            $id
        ]);
    }
}
?>
