<?php

require_once __DIR__ . "/../models/Asset.php";

class AssetController
{
    private $assetModel;

    public function __construct($db)
    {
        $this->assetModel = new Asset($db);
    }

    public function index()
    {
        return $this->assetModel->getAll();
    }

    public function store($data, $files)
    {
        try {
            $foto = '';

            if (isset($files['foto']) && $files['foto']['error'] === 0) {
                $fileName = time() . '_' . basename($files['foto']['name']);
                $target = __DIR__ . '/../uploads/' . $fileName;

                if (!is_dir(__DIR__ . '/../uploads/')) {
                    mkdir(__DIR__ . '/../uploads/', 0777, true);
                }

                if (move_uploaded_file($files['foto']['tmp_name'], $target)) {
                    $foto = 'uploads/' . $fileName;
                }
            }

            $data['foto'] = $foto;
            return $this->assetModel->create($data);
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                return "duplicate";
            }
            throw $e;
        }
    }

    public function update($id, $data, $files)
    {
        $existing = $this->assetModel->getById($id);
        $foto = $existing['foto'];

        if (
            isset($files['foto']) &&
            $files['foto']['error'] === 0
        ) {
            $fileName = time() . '_' . basename($files['foto']['name']);
            $target = __DIR__ . '/../uploads/' . $fileName;

            if (move_uploaded_file($files['foto']['tmp_name'], $target)) {
                $foto = 'uploads/' . $fileName;
                // Optional: hapus foto lama
            }
        }

        $data['foto'] = $foto;

        return $this->assetModel->update($id, $data);
    }

    public function destroy($id)
    {
        return $this->assetModel->delete($id);
    }
}
?>
